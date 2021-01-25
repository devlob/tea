<?php

namespace Devlob\Http\Responses;

class JsonResponse
{
    /**
     * Response message.
     *
     * @var
     */
    private $message;

    /**
     * Set content type.
     *
     * @param string $contentType
     *
     * @return $this
     */
    public function setContentType(string $contentType)
    {
        header("Content-Type: $contentType");

        return $this;
    }

    /**
     * Set status code.
     *
     * @param int $code
     *
     * @return $this
     */
    public function setStatusCode(int $code)
    {
        http_response_code($code);

        return $this;
    }

    /**
     * Set response message.
     *
     * @param $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * Get response message.
     */
    public function getMessage(): void
    {
        echo json_encode($this->message);
    }
}