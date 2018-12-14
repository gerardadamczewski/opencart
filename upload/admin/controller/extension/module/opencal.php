<?php

function dd($var) {
    echo '<pre>' . print_r($var, true) . '</pre>';
    die();
}

class ControllerExtensionModuleOpenCal extends Controller {

    private $error = array();

    public function index() {
        $this->document->addStyle('view/javascript/fullcalendar/fullcalendar.min.css');
        $this->document->addStyle('view/javascript/fullcalendar/fullcalendar.print.min.css', 'stylesheet', 'print');
        $this->document->addScript('view/javascript/fullcalendar/fullcalendar.min.js');

        $this->document->addStyle('view/javascript/jquery-timepicker-addon/jquery-ui.min.css');
        $this->document->addScript('view/javascript/jquery-timepicker-addon/jquery-ui.min.js');
        $this->document->addStyle('view/javascript/jquery-timepicker-addon/jquery-ui-timepicker-addon.min.css');
        $this->document->addScript('view/javascript/jquery-timepicker-addon/jquery-ui-timepicker-addon.min.js');
        $this->document->addStyle('view/javascript/simplecolorpicker/jquery.simplecolorpicker.css');
        $this->document->addScript('view/javascript/simplecolorpicker/jquery.simplecolorpicker.js');

        $this->load->language('extension/module/opencal');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('module_opencal', $_POST);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module/opencal', 'user_token=' . $this->session->data['user_token']));
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['code'])) {
            $data['error_code'] = $this->error['code'];
        } else {
            $data['error_code'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/opencal', 'user_token=' . $this->session->data['user_token'])
        );

        $data['action'] = $this->url->link('extension/module/opencal', 'user_token=' . $this->session->data['user_token']);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

        if (isset($this->request->post['module_opencal_app'])) {
            $data['module_opencal_app'] = $this->request->post['module_opencal_app'];
        } else {
            $data['module_opencal_app'] = $this->config->get('module_opencal_app');
        }

        if (isset($this->request->post['module_opencal_code'])) {
            $data['module_opencal_code'] = $this->request->post['module_opencal_code'];
        } else {
            $data['module_opencal_code'] = $this->config->get('module_opencal_code');
        }

        if (isset($this->request->post['module_opencal_client_id'])) {
            $data['module_opencal_client_id'] = $this->request->post['module_opencal_client_id'];
        } else {
            $data['module_opencal_client_id'] = $this->config->get('module_opencal_client_id');
        }

        if (isset($this->request->post['module_opencal_client_secret'])) {
            $data['module_opencal_client_secret'] = $this->request->post['module_opencal_client_secret'];
        } else {
            $data['module_opencal_client_secret'] = $this->config->get('module_opencal_client_secret');
        }

        if (isset($this->request->post['module_opencal_status'])) {
            $data['module_opencal_status'] = $this->request->post['module_opencal_status'];
        } else {
            $data['module_opencal_status'] = $this->config->get('module_opencal_status');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/opencal', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/opencal')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['module_opencal_app']) {
            $this->error['code'] = $this->language->get('error_code');
        }

        if (!$this->request->post['module_opencal_code']) {
            $this->error['code'] = $this->language->get('error_code');
        }

        return !$this->error;
    }

    protected function service() {
        /* if (!$this->config->get('module_opencal_code')  || !$this->config->get('module_opencal_app') ||  !$this->user->hasPermission('modify', 'extension/module/opencal')) {
          die();
          }

          $scriptUri = ($this->request->server['HTTPS'] ? 'https://' : 'http://') . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
          //dd($scriptUri);

          $client = new Google_Client();
          $client->setAccessType('online');
          $client->setApplicationName(trim($this->config->get('module_opencal_app')));
          $client->setClientId(trim($this->config->get('module_opencal_client_id')));
          $client->setClientSecret(trim($this->config->get('module_opencal_client_secret')));
          $client->setRedirectUri($scriptUri);
          $client->setScopes(Google_Service_Calendar::CALENDAR);
          //unneeded? $client->setDeveloperKey(trim($this->config->get('module_opencal_code')));
          if(isset($this->request->get['code'])) {
          $client->authenticate();
          $this->session->set('google_calendar_token', $client->getAccessToken());
          }

          if(isset($this->session->get['google_calendar_token'])) {
          $client->setAccessToken($this->session->get('google_calendar_token'));
          }

          if(!$client->getAccessToken()) {
          header('Location: ' . $client->createAuthUrl());
          die();
          } */
        $client = new Google_Client();
        $client->setApplicationName('Google Calendar API PHP Quickstart');
        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $client->setAuthConfig('/home/gerard/projects/opencart/upload/system/storage/credentials.json');
        $client->setAccessType('online');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = '/home/gerard/projects/opencart/upload/system/storage/token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return new Google_Service_Calendar($client);
    }

    public function deleteEvent() {
        $ret = $this->service()->events->delete('primary', $this->request->get['id']);
        $ret['result'] = empty($ret) ? true : false;
        die(json_encode($ret));
    }

    public function updateEvent() {
        $event = $this->service()->events->get('primary', $this->request->get['id']);
        $event->setSummary($this->request->get['title']);
        $updatedEvent = $this->service()->events->update('primary', $event->getId(), $event);
        $ret['result'] = $updatedEvent->getId() ? true : false;
        die(json_encode($ret));
    }

    public function insertEvent() {
        $event = new Google_Service_Calendar_Event(array(
            'summary' => $this->request->get['title'],
            'description' => $this->request->get['description'],
            'start' => array(
                'dateTime' => str_replace(' ', 'T', $this->request->get['start'])
            ),
            'end' => array(
                'dateTime' => str_replace(' ', 'T', $this->request->get['end'])
            ),
        ));

        $calendarId = 'primary';
        $event = $this->service()->events->insert($calendarId, $event);
        $ret['result'] = $event->getId() ? $event->getId() : false;
        die(json_encode($ret));
    }

    public function listEvents() {
        $events = $this->service()->events->listEvents('primary');

        while (true) {
            /*foreach ($events->getItems() as $event) {
                echo $event->getSummary();
            }*/
            $pageToken = $events->getNextPageToken();
            if ($pageToken) {
                $optParams = array('pageToken' => $pageToken);
                $events = $service->events->listEvents('primary', $optParams);
            } else {
                break;
            }
        }
        $ret['result'] = empty($events) ? false : $events;
        die(json_encode($ret));
    }

}
