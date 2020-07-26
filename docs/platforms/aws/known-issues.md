# Known issues, or things i have considered that it would be better to write them somewhere for future purposes


## AWS SAM CLI, Layer type and Makefile

When there is an `AWS::Lambda::LayerVersion` defined in `Type` property in layer definition, then AWS SAM CLI will 
use `Makefile` from project root. Also when this value is used, layers seem to be not mounted by SAM. 
**But** when there is `AWS::Serverless::LayerVersion` defined, everything works like a charm ( ͡° ͜ʖ ͡°)━☆ﾟ.*･｡ﾟ 

SAM will attempt to use Makefiles from `ContentUri` path, and layers are mounted properly. 


