# indentation check

> [Artistic Style 3.1](http://astyle.sourceforge.net/)을 기반으로 하여 소스코드 파일을 입력받아 사용자가 인덴트 검사 전 후의 차이를 알 수 있도록 인터페이스를 구성하였습니다.

## 프로젝트 필수 사항

이 프로젝트는 Windows 10 환경에서 개발되었습니다.
* MinGW (GCC)
* CMake
* XAMPP

..
..

## 사용법

#### AStyle.exe  

이 프로젝트를 실행시키기 위해선 다음 파일이 취상위 폴더(indentation-check)내에 위치하여야 합니다.

이 프로그램은 [Artistic Style](http://astyle.sourceforge.net/)을 기반으로 하여 기존 소스코드와 포맷팅된 소스코드의 차이에 대한 정보를 저장하는 파일을 추가로 생성하는 기능을 추가하였습니다.  

추가로 생성되는 파일은 다음과 같습니다.  
1) [파일이름.확장자(cpp, java..)].txt : 이 파일은 기존 소스코드의 라인을 기준으로 포맷팅된 파일과 차이가 있는 부분만 저장합니다.  

2) [파일이름.확장자(cpp, java..)]\_line.txt : 이 파일은 기존 소스코드의 라인을 기준으로 포맷팅된 파일과 매핑되는 라인에 대한 정보를 저장합니다.  

        [기존 소스코드 라인] --mapping-- [포맷팅된 소스코드 라인] [기존 소스코드 라인 하나 당 포맷팅된 소스코드 라인 수]  
        ex)  example.cpp_line.txt
        1 1 1 : 1번째 라인이 1개의 라인으로 변경되었고 그 시작은 포맷팅된 파일의 1번째 라인입니다.
        2 1 1 : 2번째 라인이 1개의 라인으로 변경되었고 그 시작은 포맷팅된 파일의 1번째 라인입니다. (1~2번째의 두 줄이 한 줄로 변경)
        3 3 2
        4 4 1
        5 5 2
        6 7 2 : 6번째 라인이 2개의 라인으로 변경되었고 그 시작은 포맷팅된 파일의 7번째 라인입니다.
        7 9 2

이 두 파일은 인덴트 검사 전후 소스코드에 차이가 있을 때만 생성됩니다.

실행파일의 생성은 [AStyle/doc/install.html](http://astyle.sourceforge.net/install.html)을 참고해주세요

#### start.cmd
* 로컬 호스트로 접속

    취상위 폴더 커맨드 창에서
    
        /indentation-check> start.cmd
    를 입력하면 

        http://localhost:8080/
    으로 프로젝트를 실행시킬 수 있습니다.


## 참고한 내용
* Artistic Style (http://astyle.sourceforge.net/)

## 라이센스

### Artistic Style
MIT License
Copyright (c) 2018 by Jim Pattee jimp03@email.com.

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.}


## ...
