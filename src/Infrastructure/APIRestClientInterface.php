<?php namespace StraTDeS\SharedKernel\Infrastructure;

interface APIRestClientInterface
{
    public function get(string $url, array $parameters = [], array $headers = []);

    public function post(string $url, array $parameters = [], array $headers = []);

    public function patch(string $url, array $parameters = [], array $headers = []);

    public function delete(string $url, array $parameters = [], array $headers = []);

    public function execute(string $url, string $method, array $parameters = [], array $headers = []);
}