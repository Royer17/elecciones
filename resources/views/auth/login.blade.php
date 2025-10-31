@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <h2 class="login-title">Acceso al Sistema</h2>
                <p class="login-subtitle">Sistema de Elecciones Escolares</p>
            </div>
            
            <div class="login-body">
                @if (session()->has('data'))
                <div class="alert alert-danger text-center">
                    <i class="fa fa-exclamation-triangle"></i> Nombre de usuario y/o Contraseña Incorrectas
                </div>
                @endif

                <form class="login-form" role="form" method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="form-label">Correo Electrónico:</label>
                        <div class="input-wrapper">
                            <input id="email" type="text" class="form-control login-input" name="email" value="{{ old('email') }}" placeholder="Ingresa tu correo electrónico">
                            @if ($errors->has('email'))
                            <span class="error-message">
                                <i class="fa fa-exclamation-circle"></i> {{ $errors->first('email') }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="form-label">Contraseña:</label>
                        <div class="input-wrapper">
                            <input id="password" type="password" class="form-control login-input" name="password" placeholder="Ingresa tu contraseña">

                            @if ($errors->has('password'))
                            <span class="error-message">
                                <i class="fa fa-exclamation-circle"></i> {{ $errors->first('password') }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary login-btn">
                                <i class="fa fa-btn fa-sign-in"></i> Iniciar Sesión
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
