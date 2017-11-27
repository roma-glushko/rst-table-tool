<?php

namespace RstTableToolBundle\Service;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class GenerateRstTableByPathService
 */
class GenerateRstTableByPathService
{
    /**
     * @param string $path
     *
     * @return string
     */
    public function execute($path)
    {
        $tableContent = '';
        $finder = new Finder();

        $finder->sortByName();
        $finder->ignoreDotFiles(true);
        $finder->ignoreVCS(true);
        $finder->files()->in($path);

        /** @var SplFileInfo $file */
        foreach ($finder->files() as $file) {
            $tableContent .= ' ' . $file->getRelativePath();
        }

        return $tableContent;
    }
}