<?php

namespace Fut;

/**
 * with this class you are able to calculate either the resource, definition or asset id
 * of a player by giving one of them
 *
 * @author Eduard Bess <eduard.bess@gmail.com>
 *
 * Class Calculator
 */
class Calculator
{
    /**
     * @var null|integer
     */
    private $assetId = null;

    /**
     * @var null|integer
     */
    private $resourceId = null;

    /**
     * @var null|integer
     */
    private $definitionId = null;

    /**
     * @var integer
     */
    private $currentVersion = 0;

    /**
     * constants as hex values
     *
     * @var integer[]
     */
    private $vars = array(
        'const'         => 0x70000000,
        'version_const' => 0x2000000,
        'version_step'  => 0x1000000
    );

    /**
     * @param integer $assetId
     */
    public function __construct($assetId = null)
    {
        $this->assetId = $assetId;
    }

    /**
     * if resource id or asset id isset it will return the definition id
     *
     * @return integer|null
     */
    public function getDefinitionId()
    {
        if ($this->definitionId === null && $this->resourceId !== null) {
            $definitionId = $this->resourceId - $this->vars['const'];
            $this->definitionId = $definitionId;
        }

        return $this->definitionId;
    }

    /**
     * if resource id isset it will return the version number of the resource id
     * if not, will return 0
     *
     * @return integer
     */
    public function getCurrentVersion()
    {
        if ($this->currentVersion === 0 && $this->resourceId !== null) {
            $tmp = $this->resourceId - $this->vars['const'];
            $times = round($tmp / $this->vars['version_step'], 0);
            $this->currentVersion = ($times == 0) ? 1 : $times - 1;
        }

        return $this->currentVersion;
    }


    /**
     * returns the asset id of a player based on given resource or definition id
     *
     * @return integer
     */
    public function getAssetId()
    {
        return $this->assetId;
    }

    /**
     * returns players resource id based on the asset id and the version
     *
     * @param  integer $version
     * @return integer
     */
    public function getResourceId($version = 1)
    {
        // if original
        if ($version == 1) {
            $versionTmp = 0;
        }
        // if special
        else {
            $versionTmp = $this->vars['version_const'] + $this->vars['version_step'] * ($version-1);
        }

        $base = $this->vars['const'] + $this->getAssetId();
        $resourceId = $versionTmp + $base;

        return $resourceId;
    }

    /**
     * sets the reference resource id
     *
     * @param integer $resourceId
     * @return $this
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;
        $this->calcAssetIdOfResourceId();

        return $this;
    }

    /**
     * define all other stuff through the definition id
     *
     * @param integer $definitionId
     * @return $this
     */
    public function setDefinitionId($definitionId)
    {
        $this->definitionId = $definitionId;
        $this->resourceId = $definitionId + $this->vars['const'];
        $this->calcAssetIdOfResourceId();

        return $this;
    }

    /**
     * calculates the asset id out of the resource id
     *
     * @return $this
     */
    private function calcAssetIdOfResourceId()
    {
        $tmp = $this->resourceId - $this->vars['const'];
        $times = round($tmp / $this->vars['version_step'], 0);
        $version = $this->vars['version_step'] * $times;
        $this->assetId = $tmp - $version;

        return $this;
    }


    public function calcBid($bid, $offset = 0) {

        if ($bid < 10000) {

            $bid = floor($bid/100) * 100;
            if ($offset < 0) {
                $offset++;
                $bid -= 100;
            } elseif ($offset > 0) {
                $offset--;
                $bid += 100;
            }

        }
        if ($bid >= 10000 && $bid < 50000) {

            $bid = floor($bid/250) * 250;
            if ($offset < 0) {
                $offset++;
                $bid -= $bid == 10000 ? 100 : 250;
            } elseif ($offset > 0) {
                $offset--;
                $bid += 250;
            }

        } elseif ($bid >= 50000 && $bid < 100000) {

            $bid = floor($bid/500)*500;
            if ($offset < 0) {
                $offset++;
                $bid -= $bid == 50000 ? 250 : 500;
            } elseif ($offset > 0) {
                $offset--;
                $bid += 500;
            }

        } elseif ($bid >= 100000) {

            $bid = floor($bid/1000)*1000;
            if ($offset < 0) {
                $offset++;
                $bid -= $bid == 100000 ? 500 : 1000;
            } elseif ($offset > 0) {
                $offset--;
                $bid += 1000;
            }

        }

        return $offset == 0 ? $bid : $this->calcBid($bid, $offset);
    }
}