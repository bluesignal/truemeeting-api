<?php
/**
 * TrueMeeting API
 * TrueMeetingClient.php
 *
 * @author    Marc Heuker of Hoek
 * @copyright 2019, BlueSignal
 * @license   MIT
 */

namespace BlueSignal\TrueMeetingApi;

use BlueSignal\TrueMeetingApi\Entity\Organization;
use BlueSignal\TrueMeetingApi\Entity\Room;
use BlueSignal\TrueMeetingApi\Entity\Token;
use BlueSignal\TrueMeetingApi\Entity\User;
use BlueSignal\TrueMeetingApi\Exception\InvalidException;
use BlueSignal\TrueMeetingApi\Exception\TrueMeetingException;
use BlueSignal\TrueMeetingApi\Exception\UnauthorizedException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use stdClass;

class TrueMeetingClient
{
    const DEFAULT_SERVER = 'https://api.truemeeting.app';

    /**
     * @var ClientInterface
     */
    private $http;
    /**
     * @var string
     */
    private $apiToken;
    /**
     * @var string
     */
    private $serverUri;


    /**
     * TrueMeetingClient constructor.
     * @param string $apiToken
     * @param string $serverUri
     * @param ClientInterface $client
     */
    public function __construct(
        string $apiToken,
        string $serverUri = self::DEFAULT_SERVER,
        ClientInterface $client = null
    ) {
        $this->apiToken = $apiToken;
        $this->serverUri = $serverUri;
        $this->http = $client ?? new Client();
    }

    /**
     * @param string $uuid
     * @return Room
     * @throws UnauthorizedException
     * @throws TrueMeetingException
     */
    public function getRoom(string $uuid): Room
    {
        $json = $this->get('room', ['uuid' => $uuid]);

        return Room::fromObject($json);
    }

    /**
     * @param string $name
     * @param string $welcomesMessage
     * @return Room
     * @throws UnauthorizedException
     * @throws TrueMeetingException
     */
    public function createRoom(string $name, string $welcomesMessage = null): Room
    {
        $json = $this->post('room', [
            'name' => $name,
            'welcome_message' => $welcomesMessage,
        ]);

        return Room::fromObject($json);
    }

    /**
     * Create a new organization
     *
     * **Reseller only**
     *
     * @param string $name
     * @return Organization
     * @throws UnauthorizedException
     * @throws TrueMeetingException
     */
    public function createOrganization(string $name): Organization
    {
        $json = $this->post('organization', [
            'name' => $name,
        ]);

        return Organization::fromObject($json);
    }

    /**
     * Creates a new user
     *
     * If no password is provided the user will receive an e-mail how to setup one
     *
     * @param User $user
     * @param string $password
     * @param string $organizationUuid|null  **Reseller only**
     * @throws TrueMeetingException
     * @throws UnauthorizedException
     */
    public function createUser(User $user, string $password = null, string $organizationUuid = null): void
    {
        $json = $this->post('user', [
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'email' => $user->getEmail(),
            'password' => $password,
        ], ['organizationUuid' => $organizationUuid]);

        User::fromObject($json, $user);
    }

    /**
     * Generate a new api token for the requested organization
     *
     * **Reseller only**
     *
     * Save this token safe and securely, it will be provided only once
     *
     * @param string $name
     * @param string $organizationUuid
     * @return Token
     * @throws TrueMeetingException
     * @throws UnauthorizedException
     */
    public function generateToken(string $name, string $organizationUuid): Token
    {
        $json = $this->post('organization/token', [
            'name' => $name,
            'uuid' => $organizationUuid,
        ]);

        return Token::fromObject($json);
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getEndpoint(string $name): string
    {
        return sprintf('%s/%s', $this->serverUri, $name);
    }

    /**
     * @param ResponseInterface $response
     * @return stdClass
     * @throws UnauthorizedException
     * @throws TrueMeetingException
     */
    protected function handleResponse(ResponseInterface $response): stdClass
    {
        $json = json_decode($response->getBody()->getContents());

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new TrueMeetingException(sprintf(
                'Server returned invalid json data: "%s"',
                json_last_error_msg()
            ));
        }

        if ($response->getStatusCode() === 400) {
            throw new InvalidException('Unable to process the request due to validation issues', $json);
        } elseif ($response->getStatusCode() === 401) {
            throw new UnauthorizedException($json->message);
        } elseif ($response->getStatusCode() >= 400) {
            throw new TrueMeetingException($json->message);
        }

        return $json;
    }

    /**
     * @param string $endpoint
     * @param array $parameters
     * @return stdClass
     * @throws UnauthorizedException
     * @throws TrueMeetingException
     */
    protected function get(string $endpoint, array $parameters = []): stdClass
    {
        $response = $this->http->get($this->getEndpoint($endpoint), [
            'headers' => $this->getDefaultHeaders($this->apiToken),
            'query' => $parameters,
            'http_errors' => false, // handleResponse will handle those
        ]);

        $json = $this->handleResponse($response);

        return $json;
    }

    /**
     * @param string $endpoint
     * @param string[] $data
     * @param string[] $parameters
     * @return stdClass
     * @throws UnauthorizedException
     * @throws TrueMeetingException
     */
    protected function post(string $endpoint, array $data = [], array $parameters = []): stdClass
    {
        $response = $this->http->post($this->getEndpoint($endpoint), [
            'headers' => $this->getDefaultHeaders($this->apiToken),
            'query' => $parameters,
            'json' => $data,
            'http_errors' => false, // handleResponse will handle those
        ]);

        $json = $this->handleResponse($response);

        return $json;
    }

    /**
     * @param string $token
     * @return string[]
     */
    protected function getDefaultHeaders(string $token): array
    {
        return [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }
}
