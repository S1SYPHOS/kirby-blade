<?php

namespace Afbora;

use Exception;
use Kirby\Cms\App as Kirby;
use Kirby\Cms\Template as KirbyTemplate;
use Afbora\Blade\Blade;
use Kirby\Toolkit\F;
use Kirby\Toolkit\Tpl;
use Kirby\Toolkit\Dir;

class Template extends KirbyTemplate
{
    protected $blade;
    protected $views;
    protected $defaultType;
    protected $name;
    protected $templates;
    protected $type;
    public static $data = [];

    public function __construct(Kirby $kirby, string $name, string $type = 'html', string $defaultType = 'html')
    {
        $this->templates = $kirby->roots()->templates();
        $this->views = $this->getPathViews();

        $this->name = strtolower($name);
        $this->type = $type;
        $this->defaultType = $defaultType;

        $this->setViewDirectory();
    }

    /**
     * Detects the location of the template file
     * if it exists.
     *
     * @return string|null
     */
    public function file(): ?string
    {
        if ($this->hasDefaultType() === true) 
        {
            try 
            {
                // Try the default template in the default template directory.
                return F::realpath($this->getFilename(), $this->root());
            } 
            catch (Exception $e) 
            {
                //
            }
            // Look for the default template provided by an extension.
            $path = Kirby::instance()->extension($this->store(), $this->name());
            if ($path !== null) 
            {
                return $path;
            }
        }
        
        $name = $this->name() . '.' . $this->type();
        
        try 
        {
            // Try the template with type extension in the default template directory.
            return F::realpath($this->getFilename(), $this->root());
        } 
        catch (Exception $e) 
        {
            // Look for the template with type extension provided by an extension.
            // This might be null if the template does not exist.
            return Kirby::instance()->extension($this->store(), $name);
        }
    }

    /**
     * @param array $data
     * @return string
     */
    public function render(array $data = []): string
    {
        if ($this->isBlade()) 
        {
            $this->blade = new Blade(
                $this->templates,
                $this->views
            );
            $this->setDirectives();
            $this->setIfStatements();

            return $this->blade->make($this->name, $data);
        } 
        else 
        {
            return Tpl::load($this->file(), $data);
        }
    }

    public function setViewDirectory()
    {
        if (!file_exists($this->views)) 
        {
            Dir::make($this->views);
        }
    }

    protected function setDirectives()
    {
        $this->blade->compiler()->directive('asset', function (string $path)
        {
            return "<?php echo asset($path) ?>";
        });

        $this->blade->compiler()->directive('csrf', function ()
        {
            return "<?php echo csrf() ?>";
        });

        $this->blade->compiler()->directive('css', function (string $url, array $options = [])
        {
            if($options)
            {
                return "<?php echo css($url, $options) ?>";
            }

            return "<?php echo css($url) ?>";
        });

        $this->blade->compiler()->directive('e', function (mixed $condition, mixed $value, mixed $alternative = null)
        {
            if($alternative)
            {
                return "<?php echo e($condition, $value, $alternative) ?>";
            }

            return "<?php echo e($condition, $value) ?>";
        });

        $this->blade->compiler()->directive('get', function (string $key, mixed $default = null)
        {
            if($default)
            {
                return "<?php echo get($key, $default) ?>";
            }

            return "<?php echo get($key) ?>";
        });

        $this->blade->compiler()->directive('gist', function (string $url, string $file = null)
        {
            if($file)
            {
                return "<?php echo gist($url, $file) ?>";
            }

            return "<?php echo gist($url) ?>";
        });

        $this->blade->compiler()->directive('h', function (string $string, bool $keepTags = false)
        {
            if($keepTags)
            {
                return "<?php echo h($string, $keepTags) ?>";
            }

            return "<?php echo h($string) ?>";
        });

        $this->blade->compiler()->directive('html', function (string $string, bool $keepTags = false)
        {
            if($keepTags)
            {
                return "<?php echo html($string, $keepTags) ?>";
            }

            return "<?php echo html($string) ?>";
        });

        $this->blade->compiler()->directive('js', function ($url, array $options = [])
        {
            if($options)
            {
                return "<?php echo js($url, $options) ?>";
            }

            return "<?php echo js($url) ?>";
        });

        $this->blade->compiler()->directive('image', function (string $path)
        {
            return "<?php echo image($path) ?>";
        });

        $this->blade->compiler()->directive('kirbytag', function (mixed $type, string $value, array $attr = [])
        {
            if($attr)
            {
                return "<?php echo kirbytag($type, $value, $attr) ?>";
            }

            return "<?php echo kirbytag($type, $value) ?>";
        });

        $this->blade->compiler()->directive('kirbytext', function (string $text, array $data = [])
        {
            if($data)
            {
                return "<?php echo kirbytext($text, $data) ?>";
            }

            return "<?php echo kirbytext($text) ?>";
        });

        $this->blade->compiler()->directive('kirbytextinline', function (string $text, array $data = [])
        {
            if($data)
            {
                return "<?php echo kirbytextinline($text, $data) ?>";
            }

            return "<?php echo kirbytextinline($text) ?>";
        });
        
        $this->blade->compiler()->directive('kt', function (string $text, array $data = [])
        {
            if($data)
            {
                return "<?php echo kirbytext($text, $data) ?>";
            }

            return "<?php echo kirbytext($text) ?>";
        });

        $this->blade->compiler()->directive('markdown', function (string $text)
        {
            return "<?php echo markdown($text) ?>";
        });

        $this->blade->compiler()->directive('option', function (string $key, mixed $default = null)
        {
            if($default)
            {
                return "<?php echo option($key, $default) ?>";
            }

            return "<?php echo option($key) ?>";
        });


        $this->blade->compiler()->directive('param', function (string $key, string $fallback = null)
        {
            if($fallback)
            {
                return "<?php echo param($key, $fallback) ?>";
            }

            return "<?php echo param($key) ?>";
        });

        $this->blade->compiler()->directive('size', function (mixed $value)
        {
            return "<?php echo size($value) ?>";
        });

        $this->blade->compiler()->directive('smartypants', function (string $text)
        {
            return "<?php echo smartypants($text) ?>";
        });

        $this->blade->compiler()->directive('snippet', function (string $name, mixed $data = null)
        {
            if($data)
            {
                return "<?php echo snippet($name, $data) ?>";
            }

            return "<?php echo snippet($name) ?>";
        });

        $this->blade->compiler()->directive('svg', function (string $file)
        {
            return "<?php echo svg($file) ?>";
        });

        $this->blade->compiler()->directive('t', function (mixed $key, string $fallback = null)
        {
            if($fallback)
            {
                return "<?php echo t($key, $fallback) ?>";
            }

            return "<?php echo t($key) ?>";
        });

        $this->blade->compiler()->directive('tc', function (mixed $key, int $count)
        {
            return "<?php echo tc($key, $count) ?>";
        });

        $this->blade->compiler()->directive('twitter', function (string $username, string $text = null, string $title = null, string $class = null)
        {
            if($text)
            {
                return "<?php echo twitter($username, $text) ?>";
            }
            elseif($text and $title)
            {
                return "<?php echo twitter($username, $text, $title) ?>";
            }
            elseif($text and $title and $class)
            {
                return "<?php echo twitter($username, $text, $title, $class) ?>";
            }

            return "<?php echo twitter($username) ?>";            
        });

        $this->blade->compiler()->directive('u', function (string $path = null, mixed $options = null)
        {
            if($options)
            {
                return "<?php echo u($path, $options) ?>";
            }

            return "<?php echo u($path) ?>";
        });

        $this->blade->compiler()->directive('url', function (string $path = null, mixed $options = null)
        {
            if($path)
            {
                return "<?php echo url($path) ?>";
            }
            elseif($path and $options)
            {
                return "<?php echo url($path, $options) ?>";
            }

            return "<?php echo url() ?>";
        });

        $this->blade->compiler()->directive('video', function (string $url, array $options = [], array $attr = [])
        {
            if($options)
            {
                return "<?php echo video($url, $options) ?>";
            }
            elseif($options and $attr)
            {
                return "<?php echo video($url, $options, $attr) ?>";
            }

            return "<?php echo video($url) ?>";
        });

        $this->blade->compiler()->directive('vimeo', function (string $url, array $options = [], array $attr = [])
        {
            if($options)
            {
                return "<?php echo vimeo($url, $options) ?>";
            }
            elseif($options and $attr)
            {
                return "<?php echo vimeo($url, $options, $attr) ?>";
            }

            return "<?php echo vimeo($url) ?>";
        });

        $this->blade->compiler()->directive('widont', function (string $string)
        {
            return "<?php echo widont($string) ?>";
        });

        $this->blade->compiler()->directive('youtube', function (string $url, array $options = [], array $attr = [])
        {
            if($options)
            {
                return "<?php echo youtube($url, $options) ?>";
            }
            elseif($options and $attr)
            {
                return "<?php echo youtube($url, $options, $attr) ?>";
            }

            return "<?php echo youtube($url) ?>";
        });

        foreach ($directives = option('afbora.blade.directives', []) as $directive => $callback) 
        {
            $this->blade->compiler()->directive($directive, $callback);
        }
    }

    protected function setIfStatements()
    {
        foreach ($statements = option('afbora.blade.ifs', []) as $statement => $callback) 
        {
            $this->blade->compiler()->if($statement, $callback);
        }
    }

    public function getFilename()
    {
        if ($this->isBlade()) 
        {
            return $this->root() . '/' . $this->name() . '.' . $this->bladeExtension();
        } 
        else 
        {
            return $this->root() . '/' . $this->name() . '.' . $this->extension();
        }
    }

    public function isBlade()
    {
        return !!file_exists($this->root() . '/' . $this->name() . '.' . $this->bladeExtension());
    }

    /**
     * Returns the expected template file extension
     *
     * @return string
     */
    public function bladeExtension(): string
    {
        return 'blade.php';
    }

    protected function getPathViews()
    {
        $path = option('afbora.blade.views');
        
        if (is_callable($path)) 
        {
            return $path();
        }
        
        return $path;
    }
}
