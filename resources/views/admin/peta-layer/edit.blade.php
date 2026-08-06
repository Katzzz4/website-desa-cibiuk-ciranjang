@extends('layouts.admin')

@section('title', 'Edit Lapisan Peta')

@section('content')
<form method="POST" action="{{ route('admin.peta-layer.update', $layer) }}" enctype="multipart/form-data" class="max-w-2xl space-y-5">
    @csrf
    @method('PUT')
    @include('admin.peta-layer._form')

    <div class="flex gap-3">
        <x-tombol.simpan label="Simpan Perubahan" />
        <x-tombol.batal :href="route('admin.peta-layer.index')" />
    </div>
</form>
@endsection