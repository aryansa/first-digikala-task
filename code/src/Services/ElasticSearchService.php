<?php

namespace App\Services;


use App\Misc\Containers\ProductSearch;
use App\Services;
use Elasticsearch\ClientBuilder;


class ElasticSearchService
{
    private $es;

    /**
     * ElasticSearchService constructor.
     */
    public function __construct()
    {
        $configs = Services::configService()->getConfig();
        $host = $configs['elasticsearch']['host'];
        $port = $configs['elasticsearch']['port'];

        $this->es = ClientBuilder::create()
            ->setHosts(["$host:$port"])
            ->build();
    }

    public function index(array $product)
    {
        $this->es->index([
            'index' => 'products',
            'type' => 'product',
            'id' => $product['id'],
            'body' => $product
        ]);
    }

    public function get($params)
    {

        return $this->es->get($params);
    }

    public function search(ProductSearch $productSearch): array
    {
        $query = [
            'index' => 'products',
            'type' => 'product',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [],
                        'filter' => []
                    ]
                ]
            ]
        ];

        if (!empty($productSearch->getKeyword())) {
            $query['body']['query']['bool']['must'] = [
                'multi_match' => [
                    'query' => $productSearch->getKeyword(),
                    'fields' => ['title', 'description']
                ]
            ];
        }

        if (!empty($productSearch->getMinPrice()) || !empty($productSearch->getMaxPrice())) {
            $query['body']['query']['bool']['filter']['range'] = [
                'variants.price' => []
            ];

            if (!empty($productSearch->getMaxPrice())) {
                $query['body']['query']['bool']['filter']['range']['variants.price']['lte'] = $productSearch->getMaxPrice();
            }

            if (!empty($productSearch->getMinPrice())) {
                $query['body']['query']['bool']['filter']['range']['variants.price']['gte'] = $productSearch->getMinPrice();
            }
        }

        if (!empty($productSearch->getColors())) {
            $query['body']['query']['bool']['filter']['terms'] = [
                'variants.color_id' => $productSearch->getColors()
            ];
        }

        try {
            $result = $this->es->search($query);
        } catch (\Exception $e) {
            return [];
        }

        $ids = array_column($result['hits']['hits'], '_id');
        return $ids;
    }

    public function delete(int $id): void
    {
        $params = [
            'index' => 'products',
            'type' => 'product',
            'id' => $id
        ];
        $this->es->delete($params);
    }

    // this piece of code was too time consuming and i think dear elasticsearch should document this .
    public function deleteVariant($pid, $id)
    {
        $update = [
            'index' => 'products',
            'type' => 'product',

            'body' => [
                'query' => [
                    'term' => [
                        "id" => $pid
                    ]
                ],
                'script' => [
                    'lang' => 'painless',
                    'inline' => '
                        for(int i=0;i<ctx._source.variants.size();i++)
                        {
                            if((ctx._source.variants.get(i)["id"]+"").equals(params.ids))
                            {
                                ctx._source.variants.remove(i);
                            }
                        }',
                    'params' => [
                        'ids' => $id,
                    ]
                ]
            ]
        ];
        $this->es->updateByQuery($update);
    }
}