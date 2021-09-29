<?php

require_once("app/lib/View.php");

abstract class ResourceController
{
    protected $view;

    public function __construct()
    {
        $this->view = new View();
    }

    abstract public function index();

    abstract public function create();

    abstract public function store(HttpRequest $request);

    abstract public function show(int $id);

    abstract public function edit(int $id);

    abstract public function update(HttpRequest $request, int $id);

    abstract public function destroy(int $id);
}