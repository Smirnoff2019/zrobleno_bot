<?php

namespace App\Telegram\QueryBuilders;

use App\Telegram\QueryBuilders\Concerns\BaseQueryBuilder;


class InlineBtnQueryBuilder extends BaseQueryBuilder
{

    /**
     * Generates a string of data for inline btn callback query
     *
     * @param string $routeName
     * @param array  $queryData
     * @return void
     */
    protected function toQuery(string $routeName, array $queryData = [])
    {
        $queryData = json_encode($queryData);
        $this->data = "$routeName:$queryData";
        logger('InlineBtnQueryBuilder->toQuery',['data' => $this->data]);
    }

}
