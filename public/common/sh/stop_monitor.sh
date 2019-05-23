#!/bin/bash
if [ $# != 1 ]; then
	echo "usage:stopMonitor [taskName]"
	exit 0
fi
ps -aux | grep $1 | grep -v "grep" | awk '{print $2}' | while read pid
do
	if [ $pid != "" ]; then
		sudo kill -9 $pid
	fi
done
echo "success"
