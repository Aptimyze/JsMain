    ~use helper = SfMinify`
    ~minify_include_stylesheets('common')`
    ~minify_include_stylesheets()`
    ~minify_include_javascripts('commonTop')`
    ~minify_include_javascripts('top')`
     ~$sf_content`
    ~minify_include_javascripts('commonBottom')`
    ~minify_include_javascripts('bottom')`
