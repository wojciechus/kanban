<?php

namespace KanbanBoard;

use \Michelf\Markdown;

class Application
{
    private $github;
    private $repositories;
    private $paused_labels;

    public function __construct(GithubClient $github, array $repositories, array $paused_labels = [])
    {
        $this->github = $github;
        $this->repositories = $repositories;
        $this->paused_labels = $paused_labels;
    }

    public function board()
    {
        $ms = array();
        foreach ($this->repositories as $repository) {
            foreach ($this->github->milestones($repository) as $data) {
                $ms[$data['title']] = $data;
                $ms[$data['title']]['repository'] = $repository;
            }
        }
        ksort($ms);
        foreach ($ms as $name => $data) {
            $issues = $this->issues($data['repository'], $data['number']);
            $percent = self::_percent($data['closed_issues'], $data['open_issues']);
            if ($percent) {
                $milestones[] = array(
                    'milestone' => $name,
                    'url' => $data['html_url'],
                    'progress' => $percent,
                    'queued' => $issues['queued'],
                    'active' => $issues['active'],
                    'completed' => $issues['completed']
                );
            }
        }
        return $milestones;
    }

    private function issues($repository, $milestone_id)
    {
        $issues = $this->github->issues($repository, $milestone_id);
        foreach ($issues as $singleIssue) {
            if (isset($singleIssue['pull_request']))
                continue;
            $issues[self::_state($singleIssue)][] = [
                'id' => $singleIssue['id'], 'number' => $singleIssue['number'],
                'title' => $singleIssue['title'],
                'body' => Markdown::defaultTransform($singleIssue['body']),
                'url' => $singleIssue['html_url'],
                'assignee' => (is_array($singleIssue) && array_key_exists('assignee', $singleIssue) && !empty($singleIssue['assignee'])) ? $singleIssue['assignee']['avatar_url'] . '?s=16' : NULL,
                'paused' => self::labels_match($singleIssue, $this->paused_labels),
                'progress' => self::_percent(
                    substr_count(strtolower($singleIssue['body']), '[x]'),
                    substr_count(strtolower($singleIssue['body']), '[ ]')),
                'closed' => $singleIssue['closed_at']
            ];
        }

        if (!empty($issues['active'])) {
            usort($issues['active'], function ($a, $b) {
                return count($a['paused']) - count($b['paused']) === 0 ? strcmp($a['title'], $b['title']) : count($a['paused']) - count($b['paused']);
            });
        }

        return $issues;
    }

    private static function _state($issue)
    {
        if ($issue['state'] === 'closed')
            return 'completed';
        else if (Utilities::hasValue($issue, 'assignee') && count($issue['assignee']) > 0)
            return 'active';
        else
            return 'queued';
    }

    private static function labels_match($issue, $needles)
    {
        if (Utilities::hasValue($issue, 'labels')) {
            foreach ($issue['labels'] as $label) {
                if (in_array($label['name'], $needles)) {
                    return array($label['name']);
                }
            }
        }

        return array();
    }

    private static function _percent($complete, $remaining)
    {
        $total = $complete + $remaining;
        if ($total > 0) {
            $percent = ($complete OR $remaining) ? round($complete / $total * 100) : 0;
            return array(
                'total' => $total,
                'complete' => $complete,
                'remaining' => $remaining,
                'percent' => $percent
            );
        }
        return array();
    }
}
