# HalfKP
![Layers](https://raw.githubusercontent.com/HalfKP/NNUE/main/Layers.png)

The neural network consists of four layers. The input layer is heavily overparametrized, feeding in the board representation for all king placements per side. The efficiency of NNUE is due to incremental update of the input layer outputs in make and unmake move, where only a tiny fraction of its neurons need to be considered [11]. The remaining three layers with 256x2x32-32x32-32x1 neurons are computational less expensive, best calculated using appropriate SIMD instructions performing fast 16-bit integer arithmetic, like SSE2 or AVX2 on x86-64, or if available, AVX-512. 

## Training Guide
### Generating Training Data
Use the "no-nnue.nnue-gen-sfen-from-original-eval" binary. The given example is generation in its simplest form. There are more commands. 
```
uci
setoption name Threads value x
setoption name Hash value y
isready
gensfen depth 8 loop 100000000
```
- `depth` is the searched depth per move, or how far the engine looks forward. This value is an integer.
- `loop` is the amount of positions generated. This value is also an integer.

Specify how many threads and how much memory you would like to use with the `x` and `y` values.
This will save a file named "generated_kifu.bin" in the same folder as the binary. Once generation is done, move the file to a folder named "trainingdata" in the same directory as the binaries.
### Generating validation data
The process is the same as the generation of training data, except for the fact that you need to set loop to 1 million, because you don't need a lot of validation data. The depth should be the same as before or a little higher than the depth of the training data. 
```
uci
setoption name Threads value x
setoption name Hash value y
isready
gensfen depth 8 loop 1000000
```
Once generation is done, move the file to a folder named "validationdata" in the same directory as the binaries.
### Training a completely new network
Use the "halfkp_256x2-32-32.nnue-learn" binary. Create an empty folder named "evalsave" in the same directory as the binaries.
```
uci
setoption name SkipLoadingEval value true
setoption name Threads value x
isready
learn targetdir trainingdata loop 100 batchsize 1000000 eta 1.0 lambda 0.5 eval_limit 32000 nn_batch_size 1000 newbob_decay 0.5 eval_save_interval 10000000 loss_output_interval 1000000 mirror_percentage 50 validation_set_file_name validationdata\generated_kifu.bin
```
- `eta` is the learning rate.
- `lambda` is the amount of weight it puts to eval of learning data vs win/draw/loss results.

Nets get saved in the "evalsave" folder. Copy the net located in the "final" folder under the "evalsave" directory and move it into a new folder named "eval" under the directory with the binaries.
