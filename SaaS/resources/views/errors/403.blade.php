{{-- resources/views/errors/403.blade.php --}}
@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __('Vous n’avez pas la permission de voir cette page.'))
