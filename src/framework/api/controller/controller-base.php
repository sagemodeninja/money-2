<?php
    namespace Framework\Api\Controller;

    use Framework\Http\HttpRequest;
    use Framework\Http\HttpResponse;

    class ControllerBase
    {
        public function handleRequest(HttpRequest $request): HttpResponse {
            $action = ControllerAction::getControllerAction($this, $request);
            
            if (!isset($action))
            {
                return $this->NotFound();
            }
            
            return $action->handle($this, $request);
        }

        # Responses
        protected function Ok(mixed $content = null)
        {
            return new HttpResponse(200, $content);
        }

        protected function Created($content = null) {
            return new HttpResponse(201, $content);
        }

        protected function NoContent() {
            return new HttpResponse(204);
        }

        protected function BadRequest(mixed $content = null) {
            return new HttpResponse(400, $content);
        }
        
        protected function NotFound(mixed $content = null) {
            return new HttpResponse(404, $content);
        }
        
        protected function InternalServerError(mixed $content = null) {
            return new HttpResponse(500, $content);
        }
    }
?>