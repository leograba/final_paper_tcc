{"filter":false,"title":"pru_main.c","tooltip":"/pru_sdk/example/pruss_c/pru_main.c","undoManager":{"mark":100,"position":100,"stack":[[{"start":{"row":43,"column":3},"end":{"row":43,"column":4},"action":"remove","lines":["\""],"id":177}],[{"start":{"row":43,"column":4},"end":{"row":43,"column":5},"action":"insert","lines":["\""],"id":178}],[{"start":{"row":43,"column":5},"end":{"row":43,"column":6},"action":"insert","lines":[" "],"id":179}],[{"start":{"row":43,"column":5},"end":{"row":43,"column":6},"action":"remove","lines":[" "],"id":180}],[{"start":{"row":32,"column":5},"end":{"row":32,"column":6},"action":"remove","lines":["\""],"id":181}],[{"start":{"row":33,"column":5},"end":{"row":33,"column":6},"action":"remove","lines":["\""],"id":182}],[{"start":{"row":34,"column":5},"end":{"row":34,"column":6},"action":"remove","lines":["\""],"id":183}],[{"start":{"row":35,"column":5},"end":{"row":35,"column":6},"action":"remove","lines":["\""],"id":184}],[{"start":{"row":30,"column":5},"end":{"row":30,"column":6},"action":"insert","lines":[" "],"id":185}],[{"start":{"row":30,"column":6},"end":{"row":30,"column":8},"action":"insert","lines":["  "],"id":186}],[{"start":{"row":30,"column":8},"end":{"row":30,"column":10},"action":"insert","lines":["  "],"id":187}],[{"start":{"row":30,"column":8},"end":{"row":30,"column":10},"action":"remove","lines":["  "],"id":188}],[{"start":{"row":30,"column":6},"end":{"row":30,"column":8},"action":"remove","lines":["  "],"id":189}],[{"start":{"row":30,"column":5},"end":{"row":30,"column":6},"action":"remove","lines":[" "],"id":190}],[{"start":{"row":30,"column":5},"end":{"row":30,"column":6},"action":"insert","lines":[" "],"id":191}],[{"start":{"row":37,"column":4},"end":{"row":37,"column":6},"action":"insert","lines":["\"\""],"id":192}],[{"start":{"row":37,"column":5},"end":{"row":37,"column":6},"action":"remove","lines":["\""],"id":193}],[{"start":{"row":38,"column":4},"end":{"row":38,"column":6},"action":"insert","lines":["\"\""],"id":194}],[{"start":{"row":38,"column":4},"end":{"row":38,"column":6},"action":"remove","lines":["\"\""],"id":195}],[{"start":{"row":38,"column":4},"end":{"row":38,"column":6},"action":"insert","lines":["\"\""],"id":196}],[{"start":{"row":38,"column":5},"end":{"row":38,"column":6},"action":"remove","lines":["\""],"id":197}],[{"start":{"row":39,"column":8},"end":{"row":39,"column":9},"action":"insert","lines":["\""],"id":198}],[{"start":{"row":40,"column":8},"end":{"row":40,"column":9},"action":"insert","lines":["\""],"id":199}],[{"start":{"row":41,"column":8},"end":{"row":41,"column":9},"action":"insert","lines":["\""],"id":200}],[{"start":{"row":42,"column":8},"end":{"row":42,"column":9},"action":"insert","lines":["\""],"id":201}],[{"start":{"row":44,"column":8},"end":{"row":44,"column":9},"action":"insert","lines":["\""],"id":202}],[{"start":{"row":45,"column":8},"end":{"row":45,"column":9},"action":"insert","lines":["\""],"id":203}],[{"start":{"row":46,"column":8},"end":{"row":46,"column":9},"action":"insert","lines":["\""],"id":204}],[{"start":{"row":47,"column":8},"end":{"row":47,"column":9},"action":"insert","lines":["\""],"id":205}],[{"start":{"row":31,"column":11},"end":{"row":31,"column":12},"action":"insert","lines":[";"],"id":206}],[{"start":{"row":36,"column":11},"end":{"row":36,"column":12},"action":"insert","lines":[";"],"id":207}],[{"start":{"row":43,"column":12},"end":{"row":43,"column":13},"action":"insert","lines":[";"],"id":208}],[{"start":{"row":24,"column":0},"end":{"row":49,"column":4},"action":"remove","lines":["  __asm__ __volatile__","  (","    \"LBCO r0, C4, 4, 4 \\n\" ","    \"CLR r0, r0, 4 \\n\"","    \"SBCO r0, C4, 4, 4 \\n\"","    ","    \" MOV r1, 10 \\n\"","    \"BLINK:; \\n\"","    \"    MOV r2, 7<<22 \\n\"","    \"    MOV r3, GPIO1 | GPIO_SETDATAOUT \\n\"","    \"    SBBO r2, r3, 0, 4 \\n\"","    \"    MOV r0, 0x00a00000 \\n\"","    \"DELAY:; \\n\"","    \"    SUB r0, r0, 1 \\n\"","    \"    QBNE DELAY, r0, 0 \\n\"","        \"MOV r2, 7<<22 \\n\"","        \"MOV r3, GPIO1 | GPIO_CLEARDATAOUT \\n\"","        \"SBBO r2, r3, 0, 4 \\n\"","        \"MOV r0, 0x00a00000 \\n\"","    \"DELAY2:; \\n\"","        \"SUB r0, r0, 1 \\n\"","        \"QBNE DELAY2, r0, 0 \\n\"","        \"SUB r1, r1, 1 \\n\"","        \"QBNE BLINK, r1, 0 \\n\"","    ","  );"],"id":209}],[{"start":{"row":23,"column":0},"end":{"row":24,"column":0},"action":"remove","lines":["",""],"id":210}],[{"start":{"row":11,"column":2},"end":{"row":11,"column":3},"action":"insert","lines":["/"],"id":211}],[{"start":{"row":11,"column":3},"end":{"row":11,"column":4},"action":"insert","lines":["/"],"id":212}],[{"start":{"row":11,"column":3},"end":{"row":11,"column":4},"action":"remove","lines":["/"],"id":213}],[{"start":{"row":11,"column":2},"end":{"row":11,"column":3},"action":"remove","lines":["/"],"id":214}],[{"start":{"row":21,"column":3},"end":{"row":21,"column":4},"action":"insert","lines":["/"],"id":215}],[{"start":{"row":21,"column":4},"end":{"row":21,"column":5},"action":"insert","lines":["/"],"id":216}],[{"start":{"row":20,"column":4},"end":{"row":20,"column":5},"action":"insert","lines":["/"],"id":217}],[{"start":{"row":20,"column":5},"end":{"row":20,"column":6},"action":"insert","lines":["/"],"id":218}],[{"start":{"row":21,"column":5},"end":{"row":21,"column":6},"action":"remove","lines":[" "],"id":219}],[{"start":{"row":21,"column":4},"end":{"row":21,"column":5},"action":"remove","lines":["/"],"id":220}],[{"start":{"row":21,"column":3},"end":{"row":21,"column":4},"action":"remove","lines":["/"],"id":221}],[{"start":{"row":21,"column":3},"end":{"row":21,"column":4},"action":"insert","lines":[" "],"id":222}],[{"start":{"row":21,"column":4},"end":{"row":21,"column":5},"action":"insert","lines":["/"],"id":223}],[{"start":{"row":21,"column":5},"end":{"row":21,"column":6},"action":"insert","lines":["/"],"id":224}],[{"start":{"row":21,"column":6},"end":{"row":21,"column":7},"action":"insert","lines":["/"],"id":225}],[{"start":{"row":21,"column":6},"end":{"row":21,"column":7},"action":"remove","lines":["/"],"id":226}],[{"start":{"row":17,"column":16},"end":{"row":17,"column":17},"action":"remove","lines":["/"],"id":227}],[{"start":{"row":17,"column":15},"end":{"row":17,"column":16},"action":"remove","lines":["*"],"id":228}],[{"start":{"row":17,"column":14},"end":{"row":17,"column":15},"action":"remove","lines":[" "],"id":229}],[{"start":{"row":17,"column":4},"end":{"row":17,"column":5},"action":"remove","lines":[" "],"id":230}],[{"start":{"row":17,"column":3},"end":{"row":17,"column":4},"action":"remove","lines":["*"],"id":231}],[{"start":{"row":17,"column":2},"end":{"row":17,"column":3},"action":"remove","lines":["/"],"id":232}],[{"start":{"row":17,"column":2},"end":{"row":17,"column":3},"action":"remove","lines":["w"],"id":233}],[{"start":{"row":17,"column":2},"end":{"row":17,"column":3},"action":"remove","lines":["h"],"id":234}],[{"start":{"row":17,"column":2},"end":{"row":17,"column":3},"action":"remove","lines":["i"],"id":235}],[{"start":{"row":17,"column":2},"end":{"row":17,"column":3},"action":"remove","lines":["l"],"id":236}],[{"start":{"row":17,"column":2},"end":{"row":17,"column":3},"action":"remove","lines":["e"],"id":237}],[{"start":{"row":17,"column":2},"end":{"row":17,"column":3},"action":"insert","lines":["f"],"id":238}],[{"start":{"row":17,"column":3},"end":{"row":17,"column":4},"action":"insert","lines":["o"],"id":239}],[{"start":{"row":17,"column":4},"end":{"row":17,"column":5},"action":"insert","lines":["r"],"id":240}],[{"start":{"row":17,"column":7},"end":{"row":17,"column":8},"action":"remove","lines":["1"],"id":241}],[{"start":{"row":17,"column":7},"end":{"row":17,"column":8},"action":"insert","lines":["i"],"id":242}],[{"start":{"row":17,"column":8},"end":{"row":17,"column":9},"action":"insert","lines":[" "],"id":243}],[{"start":{"row":17,"column":9},"end":{"row":17,"column":10},"action":"insert","lines":["="],"id":244}],[{"start":{"row":17,"column":10},"end":{"row":17,"column":11},"action":"insert","lines":[" "],"id":245}],[{"start":{"row":17,"column":11},"end":{"row":17,"column":12},"action":"insert","lines":["0"],"id":246}],[{"start":{"row":17,"column":12},"end":{"row":17,"column":13},"action":"insert","lines":[";"],"id":247}],[{"start":{"row":17,"column":13},"end":{"row":17,"column":14},"action":"insert","lines":[" "],"id":248}],[{"start":{"row":17,"column":14},"end":{"row":17,"column":15},"action":"insert","lines":["i"],"id":249}],[{"start":{"row":17,"column":15},"end":{"row":17,"column":16},"action":"insert","lines":["<"],"id":250}],[{"start":{"row":17,"column":16},"end":{"row":17,"column":17},"action":"insert","lines":["4"],"id":251}],[{"start":{"row":17,"column":17},"end":{"row":17,"column":18},"action":"insert","lines":["0"],"id":252}],[{"start":{"row":17,"column":18},"end":{"row":17,"column":19},"action":"insert","lines":["0"],"id":253}],[{"start":{"row":17,"column":19},"end":{"row":17,"column":20},"action":"insert","lines":["0"],"id":254}],[{"start":{"row":17,"column":20},"end":{"row":17,"column":21},"action":"insert","lines":[";"],"id":255}],[{"start":{"row":17,"column":21},"end":{"row":17,"column":22},"action":"insert","lines":[" "],"id":256}],[{"start":{"row":17,"column":22},"end":{"row":17,"column":23},"action":"insert","lines":["i"],"id":257}],[{"start":{"row":17,"column":23},"end":{"row":17,"column":24},"action":"insert","lines":["+"],"id":258}],[{"start":{"row":17,"column":24},"end":{"row":17,"column":25},"action":"insert","lines":["+"],"id":259}],[{"start":{"row":17,"column":26},"end":{"row":18,"column":0},"action":"remove","lines":["",""],"id":260}],[{"start":{"row":17,"column":26},"end":{"row":17,"column":28},"action":"remove","lines":["  "],"id":261}],[{"start":{"row":18,"column":26},"end":{"row":18,"column":34},"action":"remove","lines":["deadbeef"],"id":262},{"start":{"row":18,"column":26},"end":{"row":18,"column":27},"action":"insert","lines":["0"]}],[{"start":{"row":18,"column":27},"end":{"row":18,"column":28},"action":"insert","lines":["0"],"id":263}],[{"start":{"row":18,"column":28},"end":{"row":18,"column":29},"action":"insert","lines":["0"],"id":264}],[{"start":{"row":18,"column":29},"end":{"row":18,"column":30},"action":"insert","lines":["0"],"id":265}],[{"start":{"row":18,"column":30},"end":{"row":18,"column":31},"action":"insert","lines":["0"],"id":266}],[{"start":{"row":18,"column":31},"end":{"row":18,"column":32},"action":"insert","lines":["0"],"id":267}],[{"start":{"row":18,"column":32},"end":{"row":18,"column":33},"action":"insert","lines":["0"],"id":268}],[{"start":{"row":18,"column":33},"end":{"row":18,"column":34},"action":"insert","lines":["0"],"id":269}],[{"start":{"row":10,"column":2},"end":{"row":11,"column":0},"action":"insert","lines":["",""],"id":270},{"start":{"row":11,"column":0},"end":{"row":11,"column":2},"action":"insert","lines":["  "]}],[{"start":{"row":10,"column":2},"end":{"row":10,"column":3},"action":"insert","lines":["u"],"id":271}],[{"start":{"row":10,"column":3},"end":{"row":10,"column":4},"action":"insert","lines":["n"],"id":272}],[{"start":{"row":10,"column":3},"end":{"row":10,"column":4},"action":"remove","lines":["n"],"id":273}],[{"start":{"row":10,"column":2},"end":{"row":10,"column":3},"action":"remove","lines":["u"],"id":274},{"start":{"row":10,"column":2},"end":{"row":10,"column":10},"action":"insert","lines":["uint32_t"]}],[{"start":{"row":10,"column":10},"end":{"row":10,"column":11},"action":"insert","lines":[" "],"id":275}],[{"start":{"row":10,"column":11},"end":{"row":10,"column":12},"action":"insert","lines":["i"],"id":276}],[{"start":{"row":10,"column":12},"end":{"row":10,"column":13},"action":"insert","lines":[";"],"id":277}]]},"ace":{"folds":[],"scrolltop":0,"scrollleft":0,"selection":{"start":{"row":10,"column":13},"end":{"row":10,"column":13},"isBackwards":false},"options":{"guessTabSize":true,"useWrapMode":false,"wrapToView":true},"firstLineState":0},"timestamp":1445630809000,"hash":"5278ee2d097bf5676d43fa0144f63daf734de673"}