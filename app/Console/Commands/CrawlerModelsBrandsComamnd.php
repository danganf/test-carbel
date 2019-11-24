<?php

namespace App\Console\Commands;

use App\Repositories\BrandsRepository;
use App\Repositories\CarsRepository;
use App\Repositories\ModelsRepository;
use Danganf\MyClass\JsonBasic;
use Illuminate\Console\Command;

class CrawlerModelsBrandsComamnd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carbel:crawler-models-brands';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all Brands and Models';

    /**
     * Source URl Crawler.
     *
     * @var string
     */
    protected $url = 'https://seminovos.com.br/filtros?1.6.11';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // TRY/CATCH NECESSARIO PARA O CASO DA CHAMADA EXTERNA FALHAR OU DEMORAR
        try {
            $sourceJson = json_decode( file_get_contents( $this->url ), true );
        } catch (\Exception $e){
            dd( $e->getMessage() );
        }

        if( is_array( $sourceJson ) ){

            foreach ( $sourceJson as $type ){

                foreach ( $type['marcas'] as $brand ){

                    $saveBrand              = [];
                    $saveBrand['id']        = $brand['id'];
                    $saveBrand['type_id']   = $type['id'];
                    $saveBrand['type_name'] = $type['nome'];
                    $saveBrand['name']      = $brand['nome'];
                    $saveBrand['zero_km']   = $brand['0km'];
                    $saveBrand['order']     = $brand['ordem'];

                    $json = new JsonBasic();
                    $json->set( json_encode( $saveBrand ) );

                    ( new BrandsRepository() )->createOrUpdate( $json );

                    foreach ( $brand['modelos'] as $models ){
                        $saveModel               = [];
                        $saveModel['id']         = $models['id'];
                        $saveModel['brand_id']   = $brand['id'];
                        $saveModel['name']       = !empty( $models['nome'] ) ? $models['nome'] : '-';
                        $saveModel['zero_km']    = $models['0km'];
                        $saveModel['json_motor'] = !empty( $models['motor'] ) ? json_encode( $models['motor'] ) : null;

                        $json = new JsonBasic();
                        $json->set( json_encode( $saveModel ) );

                        ( new ModelsRepository() )->createOrUpdate( $json );

                    }

                }

            }

        }
    }
}
