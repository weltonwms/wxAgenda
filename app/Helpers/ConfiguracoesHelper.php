<?php
namespace App\Helpers;


class ConfiguracoesHelper
{
    public static function agendamento_ativo()
    {
        return config('agenda.agendamento_ativo',true);
    }

    public static function celula_limit()
    {
        return (int) config('agenda.celula_limit',4);
    }

    public static function desmarcacao_permitida()
    {
        return config('agenda.desmarcacao_permitida',true);
    }

    public static function desmarcacao_hours_before()
    {
        return (int) config('agenda.desmarcacao_hours_before',3);
    }

    public static function desmarcacao_limit_by_month()
    {
        return (int) config('agenda.desmarcacao_limit_by_month',4);
    }

}