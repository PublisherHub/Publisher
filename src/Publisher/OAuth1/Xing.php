<?php

namespace Publisher\OAuth1;


class Xing
{
    
    public function getGroups()
    {
        $result = $this->request('/users/me/groups', 'GET');
        return $this->parseNameAndId('groups', $result);
    }
    
    public function getForums($groupId)
    {
        $result = $this->request("/groups/$groupId/forums", 'GET');
        return $this->parseNameAndId('forums', $result);
    }
    
    /**
     * Returns key value pairs.
     * array ('name1' => 'id1', 'name2' => 'id2')
     * 
     * @param string $subject
     * @param string $result
     * @return array
     */
    protected function parseNameAndId($subject, $result)
    {
        $association = array();
        $result = json_decode($result);
        foreach ($result->$subject->items as $item) {
            $association[$item->name] = $item->id;
        }
        return $association;
    }
}

