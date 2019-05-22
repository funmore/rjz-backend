<?php
namespace App\Providers; // 名前空間を変更しているなら適宜直しましょう

use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ResponseMacroServiceProvider extends ServiceProvider
{

    public function boot(ResponseFactory $factory)
    {
        // ここではdownloadExとして登録していますが、何でも良いです
        $factory->macro('downloadEx', function($file, $name = null, array $headers = array(), $disposition = 'attachment'){
            $response = new BinaryFileResponse($file, 200, $headers, true);

            if (is_null($name))
            {
                $name = basename($file);
            }

            return $response->setContentDisposition($disposition, $name, Str::ascii($name));
        });
    }

    // registerは今回特に必要ないです
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // TODO: Implement register() method.
    }
}