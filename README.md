RPC 服务

RPC代表远程过程调用，也就是说他是我们在远程计算机上调用函数的服务，RPC服务器轻便易用，作为开发者，我们都很习惯调用函数，然后传入参数，最后得到返回值，RPC服务完全依照这种模式，它让我们可用熟悉的方式调用web服务，甚至在开发者没有经验的情况下。

我们已经看到了一些包括SOAP的示例，SOAP实际上是XML-RPC服务的一个特例，这个服务有单一的终点，我们可以调用SOAP的函数，然后向它提供任何我们需要的参数，RPC服务可以使用任何类型的数据格式，总的来说，RPC服务是十分松散的规定，这些特性对于基于函数的开放服务而言，是一种很好的选择，尤其是当现有的类库在HTTP上开放使用的时候。

使用一个RPC服务：Flickr示例
Flickr 拥有一个庞大的Web服务组，这里我们将调用Flickr 的 XML-RPC 服务作为如何合并 web 服务组或者类似服务的范例，这份针对 Flickr 的 API 文档非常细致周密：现在我们具体研究其中的方法以便从群里得到一组照片 的清单。

首先，我们需要准备发送 XML 。它包含了我们将要调用的函数名，以及我们准备传入参数的名称和值，这里我们使用 Flickr 上的 elePHPant 池作为示例。
```flick.xml```
Flickr API 调用都是通过 POST 完成的，因此我们可以使用这个调用将 XML 传递到 Flickr。由于 XML 存储在变量 $xml 中，这里有一个如何调用并从最终响应中取出数据的例子。
```index.php```

首先，我们初始化了一个 cURL 句柄指向 Flickr 的 API，表明这将是一个 POST 的请求，而且提交的数据就在 $xml 中，因此，我们应该返回而不是反馈这个响应。

然后我么调用这个 Web 服务，得到一个 XML 响应，接着我们立即从响应中创建一个 SimpleXMLElement。这个 SimpleXMLElement 将最终的响应 解析为我们可以轻松使用的结构，因此我们可以检索响应中自己感兴趣的主要部分，每个 SimpleXMLElement 的子元素也是一个 SimpleXMLElement，但是我们想要使用的是 XML 字符串,因此我们要将他转换为 字符串。

最后，我们要解析从 Web 服务响应中检索得到的 XML 。当我们使用 print_r() 来检查它的时候，会发现 SimpleXMLElement 包含一个将所有数据字段作为属性的条目，因此对照片的名称而言，我们可以这样做：

```phpt
foreach($photosxml->photo as $photo){
    echo $photo['titile'] . "\n";
}
```
数组符号对于 SimpleXMLElement 属性的用途比对象符号大得多，对象符号是用于获取对象子元素的。

#### 简历一个 RPC 服务
```phpt
ServiceFunctions.php
```
对于 WEB 服务而言，因为需要用户表明他们要调用那一个方法，所以需要指定一个接收参数的方法。为简单起见，我们假设用户想要一个JSON格式的响应，
```phpt
require 'ServiceFunctions.php';

if (isset($_GET['method'])) {
    switch ($_GET['method']) {
        case 'countWords':
            $response = ServiceFunctions::countWords($_GET['words']);
            break;
        case 'getDisplayName':
            $response = ServiceFunctions::getdisplayName($_GET['first_name'], $_GET['last_name']);
            break;
        default:
            $response = 'Unknown Method';
            break;
    }
}else{
    $response = 'Unknown Method';
}

header('Content-type: application/json');
echo json_encode($response);

```

Web服务根本就不算什么难事！我们只需要取得这个方法的参数，如果这是我们预期的值，那就相应地调用 ServiceFunctions 类中的方法。一旦我们这么做，又或者我们收到一个错误消息，我们需要格式化作为 JSON 格式的输出并返回它

作为脚本中最后一个条目，格式化输出表明我们可以轻松地重构这个部分，将响应中不同格式返回给用户的文件头或者一个进入的格式参数，一个好的 API 将会支持不同的输出和类似这样的结构，甚至错误消息也通过相同的过程输出，我饿么可用不同方式灵活地输出进行解码。

###### API和安全
> 这段代码示例中最引人注目的一点是：使用 $_GET 变量作为函数参数没有任何附加的安全性可言，当然，这纯粹是为了让示例简单一些，然而，在一个开放的 API 中发布像这样的代码是非常危险的！安全性对于 API 的重要性和对其他任何应用程序的都一样。我们必须牢记：过滤输入，避免输出，

要使用 API 中的这些方法，我们仅需发送如下 URL 的请求
```phpt


http://localhost/json-rpc.php?method=getdisplayName&firest_name=Jane&last_name=Doe
http://localhost/json-rpc.php?method=countWords&words=Mary
```
当我们将参数传入服务时，对参数采用了 URL 编码方式，我们的 RPC 示例使用了GET 请求，这些简单的形式便于测试，而且易于理解，因为我们的示例太小了，所以这是一个极佳的选择，很多RPC服务都采用 POST 的方式提交数据，当我们使用更大的数据集时这将是更好的选择，由于有时会对URL 的大小有所限制，因此不同的系统就会有不同的要求。

我们需要不关注的要点是：RPC 是相当所散的伞形关系，你能以不同方式实现这个服务，这取决与谁活着什么奖使用这个服务，也取决于我们需要传送的数据。