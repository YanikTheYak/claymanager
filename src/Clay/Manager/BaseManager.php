<?php

namespace Clay\Manager;

use Illuminate\Events\Dispatcher as Event;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\Factory as Validator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Clay\Manager\Exception\InvalidResponseException;

abstract class BaseManager
{

    protected $entity;

    protected $data;

    /**
     * @var Redirector
     */
    protected $redirector;
    /**
     * @var Validator
     */
    protected $validator;
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Event
     */
    protected $eventDispatcher;
    protected $event;

    protected $except = ['password', 'password_confirmation', '_token'];

    protected $setup = false;

    protected $route = null;

    public function setup(Validator $validator, Redirector $redirector, Request $request, Event $eventDispatcher, $entity, array $data)
    {
        $this->redirector = $redirector;
        $this->validator = $validator;
        $this->request = $request;
        $this->eventDispatcher = $eventDispatcher;

        $this->entity = $entity;
        $this->data = array_only($data, array_keys($this->getRules()));

        $this->setup = true;
    }

    abstract public function getRules();

    public function checkSetup()
    {
        new \Exception("Please instantiate the Manager using ManagerFactory::make");
    }

    public function validate()
    {
        $this->checkSetup();

        $rules = $this->getRules();

        $validation = $this->validator->make($this->data, $rules);
        if ($validation->fails()) {
            $this->invalidResponse($validation->messages()->toArray());
        }
    }

    public function prepareData(array $data)
    {
        return $data;
    }

    public function execute()
    {
        $this->validate();
        $entity = $this->save($this->prepareData($this->data));
        if ( ! is_null($this->event)) {
            $this->triggerEvent($this->event, [$this->entity]);
        }

        return $entity;
    }

    protected function save(array $data)
    {
        $this->entity->fill($data);
        $this->entity->save();

        return $this->entity;
    }

    protected function triggerEvent($event, $parameters = array())
    {
        $this->eventDispatcher->fire($event, $parameters);
    }

    public function getRouteParameters()
    {
        return array();
    }

    /**
     * @param $errors
     * @return Response
     */
    public function response(array $errors)
    {
        if ($this->request->ajax()) {
            return new JsonResponse(['errors' => $errors]);
        }

        if ( ! is_null($this->route)) {
            $redirect = $this->redirector->route($this->route, $this->getRouteParameters());
        } else {
            $redirect = $this->redirector->back();
        }

        return $redirect->withErrors($errors)->withInput($this->request->except($this->except));
    }

    protected function invalidResponse($errors = array())
    {
        throw new InvalidResponseException($this->response($errors));
    }

    // Helpers

    public function buildInRule(array $values)
    {
        return 'in:' . implode(',', array_keys($values));
    }

}
