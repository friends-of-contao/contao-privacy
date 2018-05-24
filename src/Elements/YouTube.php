<?php

/**
 * @copyright   Friends of Contao 2018
 * @author      Fritz Michael Gschwantner <https://github.com/fritzmg>
 * @package     ContaoPrivacy
 * @license     LGPL-3.0-or-later
 * @see         https://github.com/christianbarkowsky/contao-privacyconsent-bundle
 */

namespace Foc\ContaoPrivacyBundle\Elements;

use Contao\ContentYouTube;
use Contao\FilesModel;
use Contao\StringUtil;

/**
 * {@inheritdoc}
 */
class YouTube extends ContentYouTube
{
    /**
     * {@inheritdoc}
     */
    protected function compile()
    {
        if ($this->videoSplash) {
            $imageSize = StringUtil::deserialize($this->size);
            $objFile   = FilesModel::findByUuid($this->singleSRC);

            if ($objFile !== null && \is_file(TL_ROOT . '/' . $objFile->path)) {
                $this->singleSRC = $objFile->path;
                $objSplash       = new \stdClass();
                $this->addImageToTemplate($objSplash, $this->arrData, null, null, $objFile);
                $this->Template->splashImage = $objSplash;
            } else {
                $this->videoSplash = false;
            }
        }

        $size = StringUtil::deserialize($this->playerSize);

        if (!\is_array($size) || empty($size[0]) || empty($size[1])) {
            $this->Template->width  = 640;
            $this->Template->height = 360;
        } else {
            $this->Template->width  = $size[0];
            $this->Template->height = $size[1];
        }

        if ($this->youtubeNoCookie) {
            $url = 'https://www.youtube-nocookie.com/embed/' . $this->youtube;
        } else {
            $url = 'https://www.youtube.com/embed/' . $this->youtube;
        }

        if ($this->autoplay) {
            $url .= '?autoplay=1';
        }

        $this->Template->src = $url;
    }
}
