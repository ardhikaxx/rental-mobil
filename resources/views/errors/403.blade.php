@extends('layouts.error')

@section('code', '403')
@section('title', 'Akses Ditolak')
@section('heading', 'Akses ditolak')
@section('message', $exception->getMessage() ?: 'Anda tidak memiliki hak akses untuk membuka halaman ini.')
