@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verificar su E-mail') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Se ha enviado otro email con el link para restablecer la contraseña.') }}
                        </div>
                    @endif

                    {{ __('Antes de proceder, por favor, verfique que le haya llegado el link para restablecer la contraseña.') }}
                    {{ __('Si usted no recibió el email ') }}, <a href="{{ route('verification.resend') }}">{{ __('haga click aquí') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
