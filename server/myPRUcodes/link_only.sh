#!/usr/bin/env sh

export PRU_SDK_DIR=/var/www/pru_sdk
export PRU_CGT_DIR=$PRU_SDK_DIR/pru_2.0.0B2

# convert ELF to binary file pru_main.bin

$PRU_CGT_DIR/bin/hexpru \
$PRU_CGT_DIR/bin.cmd \
./pru_main.elf


# build host program

#make clean
#make START_ADDR=`./get_start_addr.sh ./pru_main.elf`
