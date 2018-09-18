<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp;


class VMailController extends Controller
{
  protected $vmail_servers;
  protected $chosen_server;
  public $guzzle_client;
  public $guzzle_options = [
    'allow_redirects' => [
      'max'             => 1,        // allow at most 10 redirects.
      'strict'          => true,      // use "strict" RFC compliant redirects.
      'referer'         => true,      // add a Referer header
      'protocols'       => ['https', 'http'],
      'track_redirects' => true
    ],
    'timeout' => 5,
    'verify' => true,
    'synchronous' => true
  ];
  public function __construct()
  {
    $this->guzzle_client = new GuzzleHttp\Client($this->guzzle_options);
    $this->vmail_servers = [];
    $this->chosen_server = null;
    foreach (explode(',',env('VMAIL_SERVERS')) as $key => $server) {
      try {
        array_push($this->vmail_servers, array('address' => $server, 'status' => json_decode($this->guzzle_client->request('GET', $server.'health')->getBody())->status));

      } catch (\Exception $e) {
      }

    }
    if(count($this->vmail_servers))
    $this->chosen_server = $this->vmail_servers[rand(0, count($this->vmail_servers)-1)];
    else {
      $this->chosen_server = null;
    }

  }

  public function verify($api_key,$email)
  {
    if($api_key == '123456789')
    {
      if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      if($this->chosen_server)
      {
        try {
          $res = json_decode($this->guzzle_client->request('GET', $this->chosen_server['address'].'json/'.$email)->getBody());
        } catch (\Exception $e) {
          $res = $e->getMessage();
        }
      }
      if(isset($res->errorDetail))
      $res->errorDetail = '';
      return response()->json([
        'data' => $res,
        'servers_online' => count($this->vmail_servers)
      ]);
    }
    else {
      return response()->json([
        'data' => null,
        'error' => 'Please insert an email address',
        'servers_online' => count($this->vmail_servers)
      ]);
    }}
    else {
      return response()->json([
        'data' => null,
        'error' => 'Invalid api key',
        'servers_online' => count($this->vmail_servers)
      ]);
    }

  }
  public function show_servers()
  {
    $names =  array('Bionic Tiger','Bionic Lion','Bionic Bear');
    $name_servers = [];
    foreach ($this->vmail_servers as $key => $server) {
      array_push($name_servers,array('no' => $key, 'name' => $names[$key], 'status' => $server['status']));
    }
    return view( 'welcome')->with([
      'servers' => $name_servers
    ]);
  }
  public function servers()
  {
    $names =  array('Bionic Tiger','Bionic Lion','Bionic Bear');
    $name_servers = [];
    foreach ($this->vmail_servers as $key => $server) {
      array_push($name_servers,array('no' => $key, 'name' => $names[$key], 'status' => $server['status']));
    }
        return response()->json($name_servers);

    }


}
