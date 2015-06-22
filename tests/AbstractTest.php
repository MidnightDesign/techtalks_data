<?php

namespace TechtalksTest;

use PHPUnit_Framework_Assert;

abstract class AbstractTest
{
    public function uniqueIds(array $files)
    {
        $idCounts = [];
        foreach ($files as $file) {
            $id = $file['filename'];
            if (!isset($idCounts[$id])) {
                $idCounts[$id] = ['count' => 0, 'paths' => []];
            }
            $idCounts[$id]['count'] += 1;
            $idCounts[$id]['paths'][] = $file['path'];
        }
        foreach ($idCounts as $id => $data) {
            PHPUnit_Framework_Assert::assertEquals(
                1, $data['count'],
                sprintf('The ID %s exists multiple times in the following files: %s.', $id, join(', ', $data['paths']))
            );
        }
    }
}