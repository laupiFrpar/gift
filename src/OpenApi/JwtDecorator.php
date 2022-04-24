<?php

namespace Lopi\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model;
use ApiPlatform\Core\OpenApi\OpenApi;

final class JwtDecorator implements OpenApiFactoryInterface
{
    /**
     * @param OpenApiFactoryInterface $decorated
     */
    public function __construct(private OpenApiFactoryInterface $decorated)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $this->buildSchemas($openApi->getComponents()->getSchemas());
        $this->buildAuthentication($openApi);
        $this->buildAuthenticationRefresh($openApi);

        return $openApi;
    }

    /**
     * Build schemas
     *
     * @param \ArrayObject $schemas
     */
    private function buildSchemas(\ArrayObject $schemas): void
    {
        $schemas['Token'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'user' => [
                    'type' => 'object',
                    'properties' => [
                        '@id' => [
                            'type' => 'string',
                            'readOnly' => true,
                        ],
                    ],
                    'readOnly' => true,
                ],
                'refresh_token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ]);

        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                    'example' => 'johndoe@example.com',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'apassword',
                ],
            ],
        ]);

        $schemas['RefreshToken'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'refresh_token' => [
                    'type' => 'string',
                    'example' => 'xxx',
                ],
            ],
        ]);
    }

    /**
     * Build authentication for swagger
     *
     * @param OpenApi $openApi
     */
    private function buildAuthentication(OpenApi $openApi): void
    {
        $pathItem = new Model\PathItem(
            ref: 'JWT Token',
            post: new Model\Operation(
                operationId: 'postCredentialsItem',
                tags: ['Token'],
                responses: [
                    '200' => [
                        'description' => 'Authenticate an user and get JWT Token',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Authenticate an user.',
                requestBody: new Model\RequestBody(
                    description: 'Generate new JWT Token',
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials',
                            ],
                        ],
                    ]),
                ),
            ),
        );

        $openApi->getPaths()->addPath('/api/authentication', $pathItem);
    }

    /**
     * Build authentication for swagger
     *
     * @param OpenApi $openApi
     */
    private function buildAuthenticationRefresh(OpenApi $openApi): void
    {
        $pathItem = new Model\PathItem(
            ref: 'Refresh JWT Token',
            post: new Model\Operation(
                operationId: 'postRefreshTokenItem',
                tags: ['Token'],
                responses: [
                    '200' => [
                        'description' => 'Refresh JWT Token',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Refresh JWT Token.',
                requestBody: new Model\RequestBody(
                    description: 'Refresh JWT Token',
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/RefreshToken',
                            ],
                        ],
                    ]),
                ),
            ),
        );

        $openApi->getPaths()->addPath('/api/authentication_refresh', $pathItem);
    }
}
