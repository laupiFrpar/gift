<?php

namespace Lopi\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\OpenApi\OpenApi;

/**
 * Adding endpoint to SwaggerUI to retrieve a JWT token
 *
 * @see https://api-platform.com/docs/core/jwt/
 */
final class JwtDecorator implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {
    }

    /**
     * @inheritdoc
     *
     * @param array<string> $context
     */
    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $this->buildSchemas($openApi->getComponents()->getSchemas());
        $this->buildSecuritySchemes($openApi->getComponents()->getSecuritySchemes());
        $this->buildAuthentication($openApi);
        $this->buildAuthenticationRefresh($openApi);

        return $openApi;
    }

    private function buildSchemas(?\ArrayObject $schemas): void
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

    private function buildSecuritySchemes(\ArrayObject $securitySchemes)
    {
        $securitySchemes['JWT'] = new \ArrayObject([
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT',
        ]);
    }

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
