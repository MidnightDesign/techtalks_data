<?php

namespace TechtalksTest;

class EventLoader extends AbstractLoader
{
    /**
     * @param string $id
     * @return boolean
     */
    public function eventExists($id)
    {
        $files = $this->getFiles();
        foreach ($files as $file) {
            if ($file['filename'] === $id) {
                return true;
            }
        }
        return false;
    }

    protected function getDirectory()
    {
        return 'events';
    }
}