<?php

namespace App\Http\Controllers;

use App\GeneralResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Support\Facades\Notification;
use Exception;

class PasswordResetOTPController extends Controller
{

    use GeneralResponse;
    
    public function sendOTP(Request $request)
    {

        $request->validate(['email' => 'required|email']);
        DB::beginTransaction();

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->generalResponseWithErrors('El correo no está registrado.', 404);
            }

            $otp = rand(100000, 999999);
            $expiresAt = Carbon::now()->addMinutes(10);

            DB::table('password_resets_otps')->updateOrInsert(
                ['email' => $request->email],
                [
                    'otp' => $otp,
                    'expires_at' => $expiresAt,
                    'created_at' => Carbon::now(),
                ]
            );

            $user->notify(new OtpNotification($otp, $user));
            DB::commit();
            
            return $this->generalResponse(null, 'Tu código ha sido enviado a tu correo electrónico. Por favor, verifica tu bandeja de entrada.');

        } catch (Exception $e) {
            DB::rollBack();
            return $this->generalResponseWithErrors('Hubo un error al enviar el OTP, intenta nuevamente más tarde.', 500);
        }
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        DB::beginTransaction();

        try {
            $otpRecord = DB::table('password_resets_otps')
                ->where('email', $request->email)
                ->where('otp', $request->otp)
                ->first();

            if (!$otpRecord) {
                return $this->generalResponseWithErrors('El código ingresado no es valido.', 400);
            }

            if (Carbon::now()->greaterThan($otpRecord->expires_at)) {
                return $this->generalResponseWithErrors('El código ingresado ha expirado.', 400);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->generalResponseWithErrors('El correo electronico ingresado no fue encontrado.', 404);
            }

            $user->password = bcrypt($request->password);
            $user->save();

            DB::table('password_resets_otps')->where('email', $request->email)->delete();

            DB::commit();

            return $this->generalResponse(null, 'La contraseña se ha restablecida correctamente.');

        } catch (Exception $e) {
            DB::rollBack();
            return $this->generalResponseWithErrors('Hubo un error al restablecer la contraseña, intenta nuevamente más tarde.');
        }
    }
}
