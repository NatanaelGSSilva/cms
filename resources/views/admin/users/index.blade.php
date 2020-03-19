@extends('adminlte::page')

@section('title','Usuários')

@section('content_header')
    <h1>
        Meus Usuarios
        <a href="{{route('users.create')}}" class="btn btn-sm btn-success">New Usuario</a>
    </h1>
@endsection

@section('content')

<div class="card">

    <div class="car-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            {{-- proximo passo listar meus usuarios --}}
             @foreach ($users as $user)
                <tr>
                    <td>{{$user->id}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                        <a href="{{route('users.edit',['user'=>$user->id])}}" class="btn btn-sm btn-info">Editar</a>
                        <form class = "d-inline"  method="POST" action="{{route('users.destroy',['user'=>$user->id])}}" onsubmit="return confirm('tem certeza que deseja excluir')">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-sm btn-danger">Excluir</button>
                        </form>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Roda a função de links --}}
{{$users->links()}}
@endsection
