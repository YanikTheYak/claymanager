<?php

namespace Clay\Manager;

use Illuminate\Events\Dispatcher as Event;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\Factory as Validator;

class ManagerFactory
{
    /**
     * @var Application
     */
    protected $app;
    /**
     * @var Validator
     */
    protected $validator;
    /**
     * @var Redirector
     */
    protected $redirector;
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var Event
     */
    protected $event;

    /**
     * @param Application $app
     * @param Validator   $validator
     * @param Redirector  $redirector
     * @param Request     $request
     * @param Event       $event
     */
    public function __construct(Application $app, Validator $validator, Redirector $redirector, Request $request, Event $event)
    {
        $this->app = $app;
        $this->validator = $validator;
        $this->redirector = $redirector;
        $this->request = $request;
        $this->event = $event;
    }

    /**
     * @param $manager
     * @param $entity
     * @param  array       $data
     * @throws \Exception
     * @return BaseManager
     */
    public function make($manager, $entity, array $data = null)
    {
        if ( ! class_exists($manager)) {
            throw new \Exception("Manager $manager not found");
        }

        if (is_subclass_of($manager, 'BaseManager')) {
            throw new \Exception("$manager needs to be an implementation of BaseManager");
        }

        if (is_null ($data)) {
            $data = $this->request->all();
        }

        $manager = $this->app->make($manager);
        $manager->setup($this->validator, $this->redirector, $this->request, $this->event, $entity, $data);

        return $manager;
    }

    public function validate($manager, $entity, array $data = null)
    {
        $this->make($manager, $entity, $data)->validate();
    }

    public function execute($manager, $entity, array $data = null)
    {
        return $this->make($manager, $entity, $data)->execute();
    }

}
