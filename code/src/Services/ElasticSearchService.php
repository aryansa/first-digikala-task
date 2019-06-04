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

    public function suggest($keyword)
    {
        $result = $this->es->search([
                'index' => 'prd',
                'type' => '_doc',
                'body' => [
                    "suggest" => [
                        "prd-suggest" => [
                            "prefix" => $keyword,
                            "completion" => [
                                "field" => "suggest"
                            ]
                        ]
                    ]
                ]
            ]
        );
        $result = array_column($result['suggest']['prd-suggest'][0]['options'], '_id');
        return $result;
    }

    public function index(array $product)
    {
        $val = $this->es->indices()->exists(['index' => 'prd']);
        if (!$val) {
            $this->es->indices()->create([
                'index' => 'prd',
                'body' => [
                    "mappings" => [
                        "properties" => [
                            "suggest" => [
                                "type" => "completion"
                            ],
                            "title" => [
                                "type" => "keyword"
                            ]
                        ]
                    ]
                ]
            ]);
        }
        $this->es->index([

            'index' => 'prd',
            'type' => '_doc',
            'id' => $product['id'],
            'body' => [
                'suggest' => [
                    $product['title'],
                    $product['description']
                ],
                'prd' => $product
            ],
        ]);
    }

    public function get($params)
    {

        return $this->es->get($params);
    }

    public function search(ProductSearch $productSearch): array
    {
        $query = [
            'index' => 'prd',
            'type' => '_doc',
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
                    'fields' => ['prd.title', 'prd.description']
                ]
            ];
        }

        if (!empty($productSearch->getMinPrice()) || !empty($productSearch->getMaxPrice())) {
            $query['body']['query']['bool']['filter']['range'] = [
                'prd.variants.price' => []
            ];

            if (!empty($productSearch->getMaxPrice())) {
                $query['body']['query']['bool']['filter']['range']['prd.variants.price']['lte'] = $productSearch->getMaxPrice();
            }

            if (!empty($productSearch->getMinPrice())) {
                $query['body']['query']['bool']['filter']['range']['prd.variants.price']['gte'] = $productSearch->getMinPrice();
            }
        }

        if (!empty($productSearch->getColors())) {
            $query['body']['query']['bool']['filter']['terms'] = [
                'prd.variants.color_id' => $productSearch->getColors()
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
            'index' => 'prd',
            'type' => '_doc',
            'id' => $id
        ];
        $this->es->delete($params);
    }

    // this piece of code was too time consuming and i think dear elasticsearch should document this .
    public function deleteVariant($pid, $id)
    {
        $update = [
            'index' => 'prd',
            'type' => '_doc',

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