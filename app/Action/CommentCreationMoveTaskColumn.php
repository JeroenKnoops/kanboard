<?php

namespace Kanboard\Action;

use Kanboard\Model\Task;

/**
 * Add a comment of the triggering event to the task description.
 *
 * @package action
 * @author  Oren Ben-Kiki
 */
class CommentCreationMoveTaskColumn extends Base
{
    /**
     * Get automatic action description
     *
     * @access public
     * @return string
     */
    public function getDescription()
    {
        return t('Add a comment log when moving the task between columns');
    }

    /**
     * Get the list of compatible events
     *
     * @access public
     * @return array
     */
    public function getCompatibleEvents()
    {
        return array(
            Task::EVENT_MOVE_COLUMN,
        );
    }

    /**
     * Get the required parameter for the action (defined by the user)
     *
     * @access public
     * @return array
     */
    public function getActionRequiredParameters()
    {
        return array('column_id' => t('Column'));
    }

    /**
     * Get the required parameter for the event
     *
     * @access public
     * @return string[]
     */
    public function getEventRequiredParameters()
    {
        return array('task_id', 'column_id');
    }

    /**
     * Execute the action (append to the task description).
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool            True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        if (! $this->userSession->isLogged()) {
            return false;
        }

        $column = $this->board->getColumn($data['column_id']);

        return (bool) $this->comment->create(array(
            'comment' => t('Moved to column %s', $column['title']),
            'task_id' => $data['task_id'],
            'user_id' => $this->userSession->getId(),
        ));
    }

    /**
     * Check if the event data meet the action condition
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool
     */
    public function hasRequiredCondition(array $data)
    {
        return $data['column_id'] == $this->getParam('column_id');
    }
}
