@extends('layouts.admin')

@section('title', 'Tambah Lapisan Peta')

@section('content')
<form method="POST" action="{{ route('admin.peta-layer.store') }}" enctype="multipart/form-data" class="max-w-2xl space-y-5">
    @csrf
    @include('admin.peta-layer._form')

    <div class="flex gap-3">
        <x-tombol.simpan />
        <x-tombol.batal :href="route('admin.peta-layer.index')" />
    </div>
</form>
@endsection