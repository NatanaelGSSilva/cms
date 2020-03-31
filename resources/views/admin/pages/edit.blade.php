@extends('adminlte::page')

@section('title','Editar Página')

@section('content_header')
    <h1> Editar Página </h1>

@endsection

@section('content')

    {{-- exibir os eventuais erros all() faz isso --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                <h5><i class="icon fas fa-ban"></i>Ocorreu um erro</h5>

            @foreach($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
            </ul>
        </div>

    @endif
    {{-- fica uma estrutura legal com o card --}}
<div class="card">

    <div class="car-body">
        <form  action="{{route('pages.update',['page'=>$page->id])}}" method = "POST" class="form-horizontal">
            @csrf
            @method('PUT')
            <div class="form-group row">

                <label for="" class="col-sm-2 col-form-label">Título</label>
                <div class="col-sm-10">

                    <input type="text" name = "title" value = "{{$page->title}}" class="form-control @error('title') is-invalid @enderror">
                </div>

            </div>
            <div class="form-group row">

                <label for="" class="col-sm-2 col-form-label">Corpo</label>
                <div class="col-sm-10">
                    <textarea name="body" id="" cols="30" rows="10" class="form-control">{{$page->body}}</textarea>
                    {{-- <input type="email" name = "email" value = "{{$user->email}}" class="form-control @error('email') is-invalid @enderror"> --}}
                </div>

            </div>

            <div class="form-group row">

                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">

                    <input type="submit" value = "Salvar" class="btn btn-success">
                </div>

            </div>
        </form>
    </div>
</div>

@endsection
