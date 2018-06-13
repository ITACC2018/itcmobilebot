<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use App\Conversations\ExampleConversation;
use BotMan\BotMan\Middleware\ApiAi;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;

class BotManController extends Controller
{
    /**
      * get request from dialogflow
    **/
    public function handle()
    {
        //\Debugbar::disable();
        $config = [
            // Your driver-specific configuration
            // "telegram" => [
            //    "token" => "TOKEN"
            // ]
        ];
        
        DriverManager::loadDriver('App\Drivers\AccDrivers');
        // // create an instance
        //$botman = app('botman');
        $botman = BotManFactory::create($config);
        // // set api token dialogflow
        //$dialogflow = ApiAi::create('aa8391216dcf48df9c26b379c1e2da7b')->listenForAction();
        $dialogflow = ApiAi::create('2829790242a343e1a8206ed95be610e6 ')->listenForAction();
        // // Apply global "received" middleware
        $botman->middleware->received($dialogflow);
        // // Apply matching middleware per hears command
        //dd($botman);
            $botman->hears('.*', function (BotMan $bot) {
                // The incoming message matched the "my_api_action" on Dialogflow
                // Retrieve Dialogflow information:
                $extras = $bot->getMessage()->getExtras();
                $apiReply = $extras['apiReply'];
                $apiAction = $extras['apiAction'];
                $apiIntent = $extras['apiIntent'];
                
                if(!empty($apiReply)){
                   $bot->reply($apiReply);
                }else{
                   $bot->reply($apiReply);
                }
            })->middleware($dialogflow);
  
        // $botman->hears('foo', function ($bot) {
        //     $bot->reply('Hello World');
        // });
        
        // start listening
        $botman->listen();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }
}
