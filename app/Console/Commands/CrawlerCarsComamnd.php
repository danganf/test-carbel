<?php

namespace App\Console\Commands;

use App\Repositories\BrandsRepository;
use App\Repositories\CarsRepository;
use Danganf\MyClass\JsonBasic;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Console\Command;

class CrawlerCarsComamnd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carbel:crawler-car';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get stock of cars in our services';

    /**
     * Source URl Crawler.
     *
     * @var string
     */
    protected $url = 'https://seminovos.com.br/_BRAND_/_MODELS_?ordenarPor=2&registrosPagina=_LIMIT_&page=_PAGE_';

    private $brands, $limit, $ttNoSync, $pages;

    /**
     * Create a new command instance.
     *
     * @param BrandsRepository $brandsRepository
     * @return void
     */
    public function __construct( BrandsRepository $brandsRepository )
    {
        parent::__construct();
        $this->brands = $brandsRepository;

        //REGISTROS POR PAGINA RETORNADO
        $this->limit = 30;

        //NUMERO DE BRANDS RETORNADOS POR EXECUÇÃO
        $this->ttNoSync = 10;

        //NUMERO DE PAGINAS VARRIDAS POR BRAND
        $this->pages = 2;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $brands = $this->brands->getNoSync($this->ttNoSync);
        if( $brands ){
            foreach ( $brands as $brand ){

                // PEGANDO X PAGINAS
                for ( $page=1; $page <= $this->pages; $page++ ){

                    $url = str_replace(
                        [ '_BRAND_', '_MODELS_', '_LIMIT_', '_PAGE_' ],
                        [ $this->prepareString( $brand['type_name'] ), $this->prepareString( $brand['name'] ), $this->limit, $page ],
                        $this->url
                    );

                    echo $url . ' [...]';

                    $crawler = new Crawler();
                    try {
                        $crawler->addContent(file_get_contents($url));
                        echo ' ==> CRAWLER OK!';
                    } catch (\Exception $e){
                        dd( $e->getMessage() . '==> LAST_PAGE: ',
                            $page . '==> LAST_PAGE: ' . $page,
                            $brand
                        );
                    }

                    $crawlerResult = $crawler->filter('div.schema-items');

                    $prod = [];
                    foreach ($crawlerResult as $key => $domElement) {

                        $this->getTagMeta( $prod, $key, $domElement );
                        $this->getTagSpan( $prod, $key, $domElement );
                        $this->getTagLink( $prod, $key, $domElement );

                    }

                    $crawlerResult = $crawler->filter('.card-features');
                    foreach ($crawlerResult as $key => $domElement) {
                        $this->getTagP( $prod, $key, $domElement );
                        $this->getTagUls( $prod, $key, $domElement );
                    }

                    $crawlerResult = $crawler->filter('figure');
                    foreach ($crawlerResult as $key => $domElement) {

                        try{
                            $img = $domElement->getElementsByTagName('img')->item(0)->getAttribute('src');
                            #DEVIDO AO LAZY-LOADED
                            if( empty( $img ) ){
                                $img = $domElement->getElementsByTagName('img')->item(0)->getAttribute('data-src');
                            }
                        } catch (\Exception $e){
                            $img = null;
                        }
                        $prod[$key]['image'] = $img;

                    }

                    if( !empty( $prod ) ){

                        $tt = count($prod);
                        echo ' <== CARROS: ' . $tt . chr(13) . chr(10);

                        foreach ( $prod as $key => $row ) {
                            $row[ 'brand_id'] = $brand['id'];
                            $prod[ $key ]     = $row;
                        }

                        $json = new JsonBasic();
                        $json->set( json_encode( $prod ) );
                        ( new CarsRepository() )->createOrUpdate( $json );

                        // SE REGISTROS ENCONTRADOS MENOR QUE A QTD POR PAGINA, NAO PRECISA IR PRA PROXIMA
                        if( $tt < $this->limit ){
                            break;
                        }

                    } else {
                        echo ' <== NENHUM CARRO' . chr(13) . chr(10);
                        break;
                    }

                };

                $this->brands->updateNoSync( $brand['id'] );

            }
        }

    }

    private function getTagUls( &$prod, $key, $domElement ){
        foreach ( $domElement->getElementsByTagName('ul') as $item ){
            $className = $item->getAttribute('class');
            switch ( $className ){
                case 'list-features':
                    foreach ( $item->getElementsByTagName('li') as $li ){
                        $prod[$key][ $li->getAttribute('title') ] = trim( strip_tags($li->nodeValue) );
                    }
                    break;
                case 'list-inline':
                    $str = '';
                    foreach ( $item->getElementsByTagName('li') as $li ){
                        $str .= trim( strip_tags($li->nodeValue) ).' ';
                    }
                    $str = trim( $str );
                    $prod[$key]['accessories'] = !empty( $str ) ? $str : null;
                    break;
            }
        }
    }

    /**
     * @param $prod
     * @param $key
     * @param $domElement
     */
    private function getTagP( &$prod, $key, $domElement ){
        foreach ( $domElement->getElementsByTagName('p') as $item ){
            $prod[$key][ $item->getAttribute('class') ] = trim( strip_tags($item->nodeValue) );
        }
    }

    /**
     * @param $prod
     * @param $key
     * @param $domElement
     */
    private function getTaglink( &$prod, $key, $domElement ){
        foreach ( $domElement->getElementsByTagName('link') as $item ){
            $prod[$key][ $item->getAttribute('itemprop') ] = str_replace('https://schema.org/','',$item->getAttribute('href'));
        }
    }

    /**
     * @param $prod
     * @param $key
     * @param $domElement
     */
    private function getTagSpan( &$prod, $key, $domElement ){
        foreach ( $domElement->getElementsByTagName('span') as $item ){
            $prod[$key][ $item->getAttribute('itemprop') ] = $item->nodeValue;
        }
    }

    /**
     * @param $prod
     * @param $key
     * @param $domElement
     */
    private function getTagMeta( &$prod, $key, $domElement ){
        foreach ( $domElement->getElementsByTagName('meta') as $item ){
            $prod[$key][ $item->getAttribute('itemprop') ] = $item->getAttribute('content');
        }
    }

    /**
     * TRATANDO NOME PARA SER USADO NA URL
     * CONVERTE "Sedan Superior" em "sedan-superior"
     * @param $string
     * @return mixed
     */
    private function prepareString($string){
        return str_replace(' ','-',strtolower( $string ));
    }
}
