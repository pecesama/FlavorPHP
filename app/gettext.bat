dir /a /b /-p /s /o:gen *.php > sources.txt
 
PATH C:\Archivos de programa\poEdit\bin
PATH C:\Program Files\poEdit\bin

 
for /f %%a in ('dir /b languages') do call :add_strings "%%a"
 
del sources.txt
 
goto :eof
 
:add_strings
xgettext --keyword=__ --language=PHP --package-name=sabrosus-lite --package-version=1.0 --no-location --no-wrap --files-from=sources.txt -j --from-code=UTF-8 -d languages/%1/messages

cd languages/%1
msgfmt messages.po
cd ../..