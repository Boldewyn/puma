<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$userlogin = getUserLogin();
if ($attachment->isremote) {
    $this->output->set_status_header('301');
    $this->output->set_header('Location: '.$attachment->location);
    $this->output->set_output('<p><a href="'.$attachment->location.'">'.h($attachment->name).'</a></p>');
} else {
    if (!file_exists(AIGAION_ATTACHMENT_DIR.'/'.$attachment->location)) {
        appendErrorMessage(sprintf(__('Attachment file could not be found: &ldquo;%s/%s&rdquo;'), '<em>&lt;attachment_directory&gt;</em>', $attachment->location));
        redirect('');
    } else {
        $this->output->set_header('Content-Type: '.$attachment->mime);
        if ($userlogin->getPreference('newwindowforatt') == 'TRUE') {
            $this->output->set_header('Content-Disposition: inline; filename="'.$attachment->name.'"');
            $this->output->set_header('Title: "'.$attachment->name.'"');
        } else {
            $this->output->set_header('Content-Disposition: attachment; filename="'.$attachment->name.'"');
        }
        $this->output->set_header('Cache-Control: cache, must-revalidate');
        $this->output->set_header('Pragma: public');
        readfile(AIGAION_ATTACHMENT_DIR.'/'.$attachment->location);
    }
}

//__END__