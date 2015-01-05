---
layout: post
title: "Strip() to remove non-space characters from a string"
date:   2015-01-05 17:55:00
---

The following method can `strip` non-space characters from a string as well.

{% highlight ruby %}
def strip(str, char)
   new_str = ""
   str.each_byte do |byte|
      new_str << byte.chr unless byte.chr == char
   end
   new_str
end
{% endhighlight %}   

Example

{% highlight ruby %}
  strip('Hello World','o')  #=> 'Hell wrld'
{% endhighlight %}   