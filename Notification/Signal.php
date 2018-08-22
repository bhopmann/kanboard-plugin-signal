<?php

namespace Kanboard\Plugin\Signal\Notification;

use Kanboard\Core\Base;
use Kanboard\Core\Notification\NotificationInterface;
use Kanboard\Model\TaskModel;
use Kanboard\Model\SubtaskModel;
use Kanboard\Model\CommentModel;
use Kanboard\Model\TaskFileModel;

/**
 * Signal Notification
 *
 * @package  Kanboard\Plugin\Signal
 * @author   Benedikt Hopmann
 */

 // Helper functions

function clean($string)
{
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-.]/', '', $string); // Removes special chars.
}

// Overloaded classes

class Signal extends Base implements NotificationInterface
{

    /**
     * Send notification to a user
     *
     * @access public
     * @param  array     $user
     * @param  string    $eventName
     * @param  array     $eventData
     */
    public function notifyUser(array $user, $eventName, array $eventData)
    {
        $signal_to = $this->userMetadataModel->get($user['id'], 'signal_cli_receiver', $this->configModel->get('signal_cli_receiver'));
        $signal_cli_config = $this->userMetadataModel->get($user['id'], 'signal_cli_config', $this->configModel->get('signal_cli_config'));
        $signal_cli_user = $this->userMetadataModel->get($user['id'], 'signal_cli_user', $this->configModel->get('signal_cli_user'));
        $signal_forward_attachments = $this->userMetadataModel->get($user['id'], 'signal_forward_attachments', $this->configModel->get('signal_forward_attachments'));

        $signal_cli_path = $this->userMetadataModel->get($user['id'], 'signal_cli_path', $this->configModel->get('signal_cli_path'));
        $signal_tmp_dir = $this->userMetadataModel->get($user['id'], 'signal_tmp_dir', $this->configModel->get('signal_tmp_dir'));
        $signal_java_home_path = $this->userMetadataModel->get($user['id'], 'signal_java_home_path', $this->configModel->get('signal_java_home_path'));

        if (! empty($signal_to) and ! empty($signal_cli_config) and ! empty($signal_cli_user))
        {
            if ($eventName === TaskModel::EVENT_OVERDUE)
            {
                foreach ($eventData['tasks'] as $task)
                {
                    $project = $this->projectModel->getById($task['project_id']);
                    $eventData['task'] = $task;
                    $this->sendMessage($signal_cli_path, $signal_java_home_path, $signal_tmp_dir, $signal_cli_user, $signal_cli_config, $signal_to, $signal_forward_attachments, $project, $eventName, $eventData);
                }
            } else
            {
                $project = $this->projectModel->getById($eventData['task']['project_id']);
                $this->sendMessage($signal_cli_path, $signal_java_home_path, $signal_tmp_dir, $signal_cli_user, $signal_cli_config, $signal_to, $signal_forward_attachments, $project, $eventName, $eventData);
            }
        }
    }

    /**
     * Send notification to a project
     *
     * @access public
     * @param  array     $project
     * @param  string    $eventName
     * @param  array     $eventData
     */
    public function notifyProject(array $project, $eventName, array $eventData)
    {
      $signal_cli_config = $this->userMetadataModel->get($user['id'], 'signal_cli_config', $this->configModel->get('signal_cli_config'));
      $signal_cli_user = $this->userMetadataModel->get($user['id'], 'signal_cli_user', $this->configModel->get('signal_cli_user'));
      $signal_to = "-g".$this->userMetadataModel->get($user['id'], 'signal_cli_group', $this->configModel->get('signal_cli_group'));
      $signal_forward_attachments = $this->userMetadataModel->get($user['id'], 'signal_forward_attachments', $this->configModel->get('signal_forward_attachments'));

      $signal_cli_path = $this->userMetadataModel->get($user['id'], 'signal_cli_path', $this->configModel->get('signal_cli_path'));
      $signal_tmp_dir = $this->userMetadataModel->get($user['id'], 'signal_tmp_dir', $this->configModel->get('signal_tmp_dir'));
      $signal_java_home_path = $this->userMetadataModel->get($user['id'], 'signal_java_home_path', $this->configModel->get('signal_java_home_path'));

        if (! empty($signal_to) and ! empty($signal_cli_config) and ! empty($signal_cli_user))
        {
            $this->sendMessage($signal_cli_path, $signal_java_home_path, $signal_tmp_dir, $signal_cli_user, $signal_cli_config, $signal_to, $signal_forward_attachments, $project, $eventName, $eventData);
        }
    }

    /**
    * Send message to Signal
    *
    * @access private
    * @param  string    $signal_cli_path
    * @param  string    $signal_java_home_path
    * @param  string    $signal_tmp_dir
    * @param  string    $signal_cli_user
    * @param  string    $signal_cli_config
    * @param  string    $signal_to
    * @param  string    $signal_forward_attachments
    * @param  array     $project
    * @param  string    $eventName
    * @param  array     $eventData
    */
    protected function sendMessage($signal_cli_path, $signal_java_home_path, $signal_tmp_dir, $signal_cli_user, $signal_cli_config, $signal_to, $signal_forward_attachments, array $project, $eventName, array $eventData)
    {
      // Get required data

      if ($this->userSession->isLogged())
      {
          $author = $this->helper->user->getFullname();
          $title = $this->notificationModel->getTitleWithAuthor($author, $eventName, $eventData);
      }
      else
      {
          $title = $this->notificationModel->getTitleWithoutAuthor($eventName, $eventData);
      }

      $proj_name = isset($eventData['project_name']) ? $eventData['project_name'] : $eventData['task']['project_name'];
      $task_title = $eventData['task']['title'];
      $task_url = $this->helper->url->to('TaskViewController', 'show', array('task_id' => $eventData['task']['id'], 'project_id' => $project['id']), '', true);

      $attachment = '';

      // Build message

      $message = "[".htmlspecialchars($proj_name, ENT_NOQUOTES | ENT_IGNORE)."]\n";
      $message .= htmlspecialchars($title, ENT_NOQUOTES | ENT_IGNORE)."\n";

      // Add additional informations

      $description_events = array(TaskModel::EVENT_CREATE, TaskModel::EVENT_UPDATE, TaskModel::EVENT_USER_MENTION);
      $subtask_events = array(SubtaskModel::EVENT_CREATE, SubtaskModel::EVENT_UPDATE, SubtaskModel::EVENT_DELETE);
      $comment_events = array(CommentModel::EVENT_UPDATE, CommentModel::EVENT_CREATE, CommentModel::EVENT_DELETE, CommentModel::EVENT_USER_MENTION);

      if (in_array($eventName, $subtask_events))  // For subtask events
      {
          $subtask_status = $eventData['subtask']['status'];
          $subtask_symbol = '';

          if ($subtask_status == SubtaskModel::STATUS_DONE)
          {
              $subtask_symbol = '❌ ';
          }
          elseif ($subtask_status == SubtaskModel::STATUS_TODO)
          {
              $subtask_symbol = '';
          }
          elseif ($subtask_status == SubtaskModel::STATUS_INPROGRESS)
          {
              $subtask_symbol = '🕘 ';
          }

          $message .= " ↳ ".$subtask_symbol . htmlspecialchars($eventData['subtask']['title'], ENT_NOQUOTES | ENT_IGNORE)."\n";
      }

      elseif (in_array($eventName, $description_events))  // If description available
      {
          if ($eventData['task']['description'] != '')
          {
              $message .= "✏️ ".htmlspecialchars($eventData['task']['description'], ENT_NOQUOTES | ENT_IGNORE)."\n";
          }
      }

      elseif (in_array($eventName, $comment_events))  // If comment available
      {
          $message .= "💬 ".htmlspecialchars($eventData['comment']['comment'], ENT_NOQUOTES | ENT_IGNORE)."\n";
      }

      elseif ($eventName === TaskFileModel::EVENT_CREATE and $signal_forward_attachments)  // If attachment available
      {
          $file_path = getcwd()."/data/files/".$eventData['file']['path'];
          $file_name = $eventData['file']['name'];

          $message_attachment = '📎 '.$file_name;

          if (is_writable($signal_tmp_dir))
          {
              mkdir($signal_tmp_dir."/kanboard_signal_plugin");
              $attachment = $signal_tmp_dir."/kanboard_signal_plugin/".clean($file_name);
              file_put_contents($attachment, file_get_contents($file_path));
          }
          else
          {
              $attachment = $file_path;
          }
      }

      // Add URL

      if ($this->configModel->get('application_url') !== '')
      {
          $message .= "📝 ".htmlspecialchars($task_title, ENT_NOQUOTES | ENT_IGNORE) . ": ".$task_url;
      }
      else
      {
          $message .= htmlspecialchars($task_title, ENT_NOQUOTES | ENT_IGNORE);
      }

      if (! empty($signal_java_home_path) and ! empty($signal_cli_path))
      {
          $locale = $this->languageModel->getCurrentLanguage().'.UTF-8';
          setlocale(LC_ALL,$locale);
          putenv('LC_ALL='.$locale);
          putenv("JAVA_HOME=$signal_java_home_path");

          // Call signal-cli without attachment
          exec("$signal_cli_path --config \"$signal_cli_config\" -u $signal_cli_user send -m \"$message\" $signal_to > /dev/null &");

          if ($attachment != '')
          {
              // Call signal-cli with attachment and remove temporary file
              exec("($signal_cli_path --config \"$signal_cli_config\" -u $signal_cli_user send -m \"$message_attachment\" $signal_to -a \"$attachment\"; rm -rf \"$signal_tmp_dir/kanboard_signal_plugin\") > /dev/null &");
          }
      }
    }
}
