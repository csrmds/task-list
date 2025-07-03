<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- <title>@yield('title', 'Task List')</title> -->

        {{-- Materialize CSS (se quiser CDN, deixe isso, senão remova e use import via app.js) --}}
        {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet"> --}}

        {{-- Material Icons do Google --}}
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        {{-- Estilo adicional --}}
        <style>
            /* body {
                padding-top: 64px; /* Espaço para a navbar fixa 
            } */
        </style>
    </head>
