<?php

namespace RstTableToolBundle\Data;

/**
 * Class FilesystemNodeData
 */
class FilesystemNodeData
{
    protected $nodeName = '';

    /**
     * @var FilesystemNodeData[]
     */
    protected $filesystemNodes = [];

    /**
     * FilesystemNodeData constructor.
     *
     * @param $nodeName
     */
    public function __construct($nodeName)
    {
        $this->nodeName = $nodeName;
    }

    /**
     * @return string
     */
    public function getNodeName()
    {
        return $this->nodeName;
    }

    /**
     * @param string $path
     *
     * @return FilesystemNodeData
     */
    public function addFilesystemNodeByPath($path)
    {
        $pathElements = explode(DIRECTORY_SEPARATOR, $path);
        $nodeName = array_shift($pathElements);

        $filesystemNode = $this->getFilesystemNodeByName($nodeName);

        if (null === $filesystemNode) {
            $filesystemNode = new FilesystemNodeData($nodeName);
            $this->filesystemNodes[] = $filesystemNode;
        }

        if (count($pathElements) > 0) {
            $filesystemNode->addFilesystemNodeByPath(implode(DIRECTORY_SEPARATOR, $pathElements));
        }

        return $filesystemNode;
    }

    /**
     * @param $nodeName
     *
     * @return FilesystemNodeData|null
     */
    public function getFilesystemNodeByName($nodeName)
    {
        if (count($this->filesystemNodes) > 0) {
            foreach ($this->filesystemNodes as $filesystemNode) {
                if ($nodeName == $filesystemNode->getNodeName()) {
                    return $filesystemNode;
                }
            }
        }

        return null;
    }

    /**
     * @param $index
     *
     * @return FilesystemNodeData|null
     */
    public function getFilesystemNodeByIndex($index)
    {
        return array_key_exists($index, $this->filesystemNodes) ? $this->filesystemNodes[$index] : null;
    }

    /**
     * @return array
     */
    public function getFilesystemNodes()
    {
        return $this->filesystemNodes;
    }

    /**
     * @param int $depth
     *
     * @return int
     */
    public function getMaxFilesystemNodeNameLengthByDepth($depth)
    {
        if (count($this->filesystemNodes) == 0) {
            return 0;
        }

        if (0 == $depth) {
            $maxLength = 0;

            foreach ($this->filesystemNodes as $filesystemNode) {
                if ($maxLength < strlen($filesystemNode->getNodeName())) {
                    $maxLength = strlen($filesystemNode->getNodeName());
                }
            }

            return $maxLength;
        }

        $maxLength = 0;

        foreach ($this->filesystemNodes as $filesystemNode) {
            $branchMaxLength = $filesystemNode->getMaxFilesystemNodeNameLengthByDepth($depth - 1);

            if ($maxLength < $branchMaxLength) {
                $maxLength = $branchMaxLength;
            }
        }

        return $maxLength;
    }

    /**
     * @return int
     */
    public function getMaxDepth()
    {
        $result = 0;

        if (count($this->filesystemNodes) > 0) {
            foreach ($this->filesystemNodes as $filesystemNode) {
                $branchDepth = 1 + $filesystemNode->getMaxDepth();

                if ($result < $branchDepth) {
                    $result = $branchDepth;
                }
            }
        }

        return $result;
    }
}