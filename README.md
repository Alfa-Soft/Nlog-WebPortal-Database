# Nlog-WebPortal  | Gui & Database
Many .net projects and many logfiles?  
This guide bring a few smart projects togehter.

![](https://github.com/Alfa-Soft/Nlog-WebPortal/blob/main/NLLogWSInfo.png)


## Demo
[http://nlogdemo.zytes.de/LogAnalyzer/](http://nlogdemo.zytes.de/LogAnalyzer/ "http://nlogdemo.zytes.de/LogAnalyzer/")  
Login User: demo3  
Login Pass: demo3


## Tools we need
-  Webserver with PHP > 5 & MySQL to install the app
-  Adiscon LogAnalyzer a Web Syslog and IT Event Viewer.
*It is a php viewer application for event log databases like syslog-ng or windows events.>it is a php viewer application for event log databases like syslog-ng or windows events.*
- A webservice, interface between NLog and LogAnalyzer database. T
*The webservice works as NLog target, receives log-events, creates projects, fills MySQL database*
- Your .net App with NLog

<a href="http://nlogdemo.zytes.de/" target="_blank">Full install guide</a>
[Full install guide and description](http://nlogdemo.zytes.de/ "Full install guide")


## Filelist
| | |
| - | - |
|  NLogWebservice |  PHP App) |
|  NLogConfig_Template | *(Template of nlog.config to cosume webservice)* |
|   Loganalyzer-4.1.11_NLogDesign |  *copy of [LogAnalyzer](https://github.com/rsyslog/loganalyzer "LogAnalyzer") with some design changes* |
| NLogTargetTester   |  *small tool to test your setup* |



