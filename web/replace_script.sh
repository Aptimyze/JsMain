#!/bin/bash
# Script to replace a string with the specified string
# recursively inside directories.

function howto
{
        echo ""
        echo "Alternate way for replace command"
        echo "Please provide search and replace strings."
        echo "eg: $0 <searchstring> <replacestring>."
        echo "Special characters need to be escaped."
        echo ""
}

#check for required parameters
if  [ ${#1} -gt 0  ]  &&  [ ${#2} -gt 0  ];then
        for f in `find  -type f`;
                do
                        if grep -q "$1" $f;then
				if (ls $f | grep "svn-base" > /dev/null) || (ls $f | grep ".doc" > /dev/null) || (ls $f | grep ".htm.php" > /dev/null);then
                                        cp $f /home/developer/tempupload/21july/extra/
                                else
                                        cp $f $f.bak
                                        cp $f /home/developer/tempupload/21july/old/
                                        echo "$1 replaced with $2 in $f"
                                        sed s/"$1"/"$2"/g < $f.bak > $f
                                        rm $f.bak
                                        cp $f /home/developer/tempupload/21july/new/
                                fi
                        fi
                done
else
#print usage informamtion 
howto
fi

