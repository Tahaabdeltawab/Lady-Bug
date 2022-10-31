<?php

namespace App\Http\Helpers;


class Helper
{
    public static function pag($allCount, $perPage, $page)
    {
        $pag['itemsCount'] = $allCount;
        $pag['perPage'] = (int) ($perPage ?? 10);
        $pag['pagesCount'] = ceil($pag['itemsCount'] / $pag['perPage']);
        $pag['currentPage'] = (int) ($page ?? 1);
        $pag['next'] = $pag['pagesCount'] > $pag['currentPage'] ? $pag['currentPage'] + 1 : null;
        $pag['prev'] = $pag['currentPage'] - 1 ?: null;
        $pag['skip'] = ($pag['currentPage'] - 1) * $pag['perPage'];
        return $pag;
    }
}
