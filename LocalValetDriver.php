<?php

class LocalValetDriver extends WordPressValetDriver {
	// Change this to a public domain you want to use for any missing images.
	const PUBLIC_DOMAIN = 'example.com';
	
	const PHOTON_BASE = 'https://i0.wp.com/';

	/**
	 * Redirects a given URI to the public domain, via Photon, including Photon resizing.
	 *
	 * @param string $uri
	 * @return void
	 */
	public function redirectToPhoton(string $uri) : void {
		$path = $this->getFilePath($uri);
		$imageParts = $this->extractImageParts($path);

		if ($imageParts) {
			$this->redirect302(
				self::PHOTON_BASE .
				self::PUBLIC_DOMAIN .
				$imageParts['base'] .
				'.' .
				$imageParts['extension'] .
				'?resize=' .
				$imageParts['width'] .
				',' .
				$imageParts['height']
			);
		} else {
			$this->redirect302(
				self::PHOTON_BASE .
				self::PUBLIC_DOMAIN .
				$path
			);
		}
	}

	/**
	 * Issues a 302 redirect.
	 *
	 * @param string $uri
	 * @return void
	 */
	public function redirect302(string $uri) : void {
		header("Location: $uri", 302);
		die;
	}

	/**
	 * For WordPress-resized images, extracts the base, width, height, and extension.
	 * Returns an empty array if it is not a resized image.
	 *
	 * @param string $path
	 * @return array
	 */
	public function extractImageParts(string $path) : array {
		if (preg_match("#^(?P<base>.*)-(?<width>[0-9]+)x(?P<height>[0-9]+)\.(?<extension>jpe?g|gif|png)$#i", $path, $matches)) {
				return [
					'base' => $matches['base'],
					'width' => $matches['width'],
					'height' => $matches['height'],
					'extension' => $matches['extension'],
				];
		} else {
			return [];
		}
	}

	/**
	 * Returns the path portion of a URI.
	 *
	 * @param string $uri
	 * @return string
	 */
	public function getFilePath(string $uri) : string {
		return parse_url($uri, PHP_URL_PATH);
	}

	/**
	 * Whether a given URI looks like an image.
	 *
	 * @param string $uri
	 * @return bool
	 */
	public function isImage(string $uri) : bool {
		return preg_match('#\.(jpe?g|gif|png)$#i', $this->getFilePath($uri)) > 0;
	}

	/**
	 * Whether a given URI looks like an image and is not present on the local filesystem.
	 *
	 * @param string $sitePath
	 * @param string $uri
	 * @return bool
	 */
	public function isMissingImage(string $sitePath, string $uri) : bool {
		return !file_exists($sitePath . $this->getFilePath($uri)) && $this->isImage($uri);
	}

	/**
	 * Determine if the driver serves the request.
	 *
	 * @param  string  $sitePath
	 * @param  string  $siteName
	 * @param  string  $uri
	 * @return bool
	 */
	public function serves($sitePath, $siteName, $uri) {
		if ('example.com' === self::PUBLIC_DOMAIN) {
			die('You must change PUBLIC_DOMAIN in LocalValetDriver.php to be the public domain for your site');
		}

		if ($this->isMissingImage($sitePath, $uri)) {
			$this->redirectToPhoton($uri);
		}

		return true;
	}
}
