<?php

namespace RstTableToolBundle\Service;

use RstTableToolBundle\Data\FilesystemNodeData;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class GenerateRstTableByPathService
 */
class GenerateRstTableByPathService
{
    /**
     * Generates RST table by path and save it to the file
     *
     * @param string $path
     * @param string $outputFile
     * @param string[] $excludedPathList
     *
     * @return string
     */
    public function execute($path, $outputFile, $excludedPathList)
    {
        $filesystemTreeData = new FilesystemNodeData('root');

        /** @var SplFileInfo $file */
        foreach ($this->getFiles($path) as $file) {

            $path = $file->getRelativePathname();

            if ($this->shouldBeExcluded($path, $excludedPathList)) {
                continue;
            }

            $filesystemTreeData->addFilesystemNodeByPath($path);
        }

        $tableContent = $this->renderRstTable($filesystemTreeData);

        $filesystem = new Filesystem();

        $filesystem->dumpFile($outputFile, $tableContent);
    }

    /**
     * Retrieves files by path
     *
     * @param string $path
     *
     * @return SplFileInfo[]
     */
    protected function getFiles($path)
    {
        $finder = new Finder();

        $finder->sortByName();
        $finder->ignoreDotFiles(true);
        $finder->ignoreVCS(true);
        $finder->files()->in($path);

        return $finder->files();
    }

    /**
     * @param string $path
     * @param string[] $excludedPathList
     *
     * @return bool
     */
    protected function shouldBeExcluded($path, $excludedPathList)
    {
        foreach ($excludedPathList as $excludedPath) {
            if (strstr($path, $excludedPath)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param FilesystemNodeData $filesystemTreeData
     *
     * @return string
     */
    protected function renderRstTable(FilesystemNodeData $filesystemTreeData)
    {
        $tableContent = '';
        $columnWidthList = [];
        $columnCount = $filesystemTreeData->getMaxDepth();

        if (!$columnCount) {
            return '';
        }

        for ($i = 0; $i < $columnCount; $i++) {
            $columnWidthList[$i] = $filesystemTreeData->getMaxFilesystemNodeNameLengthByDepth($i);
        }

        $tableContent .= $this->renderRstTableHeader($columnWidthList);

        foreach ($filesystemTreeData->getFilesystemNodes() as $filesystemNode) {
            $tableContent .= $this->renderFilesystemNode($filesystemNode, $columnWidthList, 0);
        }

        return $tableContent;
    }

    /**
     * @param FilesystemNodeData $filesystemTreeData
     * @param int[] $columnWidthList
     * @param int $depth
     *
     * @return string
     */
    protected function renderFilesystemNode(FilesystemNodeData $filesystemTreeData, $columnWidthList, $depth)
    {
        $result = $this->renderTableRow($filesystemTreeData, $columnWidthList, $depth);

        if (count($filesystemTreeData->getFilesystemNodes()) > 0) {
            foreach ($filesystemTreeData->getFilesystemNodes() as $filesystemNode) {
                $result .= $this->renderFilesystemNode($filesystemNode, $columnWidthList, $depth + 1);
            }
        }

        return $result;
    }

    /**
     * @param FilesystemNodeData $filesystemTreeData
     * @param int[] $columnWidthList
     * @param int $depth
     *
     * @return string
     */
    protected function renderTableRow(FilesystemNodeData $filesystemTreeData, $columnWidthList, $depth)
    {
        $rowContent = '';

        for ($i = 0; $i < count($columnWidthList); $i++) {
            $columnWidth = $columnWidthList[$i];

            if ($i == 0) {
                $rowContent .= '|';
            }

            if ($depth == $i) {
                $columnValue = $filesystemTreeData->getNodeName();
                $whitespacesToAdd = $columnWidth - strlen($filesystemTreeData->getNodeName());
                $rowContent .= ' ' . $columnValue . str_repeat(' ', $whitespacesToAdd) . ' |';
            } else {
                $rowContent .= ' ' . str_repeat(' ', $columnWidthList[$i]) . ' |';
            }
        }

        $rowContent .= PHP_EOL;

        for ($i = 0; $i < count($columnWidthList); $i++) {

            if ($i == 0) {
                $rowContent .= '+';
            }

            $rowContent .= '-' . str_repeat('-', $columnWidthList[$i]) . '-+';
        }

        $rowContent .= PHP_EOL;

        return $rowContent;
    }

    /**
     * @param int[] $columnWidthList
     *
     * @return string
     */
    protected function renderRstTableHeader($columnWidthList)
    {
        $rowContent = '';

        for ($i = 0; $i < count($columnWidthList); $i++) {

            if ($i == 0) {
                $rowContent .= '+';
            }

            $rowContent .= '-' . str_repeat('-', $columnWidthList[$i]) . '-+';
        }

        $rowContent .= PHP_EOL;

        for ($i = 0; $i < count($columnWidthList); $i++) {
            if ($i == 0) {
                $rowContent .= '|';
            }

            $rowContent .= ' ' . str_repeat(' ', $columnWidthList[$i]) . ' |';
        }

        $rowContent .= PHP_EOL;


        for ($i = 0; $i < count($columnWidthList); $i++) {

            if ($i == 0) {
                $rowContent .= '+';
            }

            $rowContent .= '=' . str_repeat('=', $columnWidthList[$i]) . '=+';
        }

        $rowContent .= PHP_EOL;

        return $rowContent;
    }
}